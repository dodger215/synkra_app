<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\StockLocation;
use App\Models\StockBin;
use App\Models\StockMovement;
use App\Models\StockMovementType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StocksImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $tenantId;

    public function __construct()
    {
        $this->tenantId = Auth::user()->tenant_id;
    }

    public function collection(Collection $rows)
    {
        $movementType = StockMovementType::firstOrCreate(
            ['name' => 'initial_import'],
            ['movement_direction' => 'in', 'affects_balance' => true]
        );

        DB::transaction(function () use ($rows, $movementType) {
            foreach ($rows as $row) {
                // Find Product
                $product = Product::where('tenant_id', $this->tenantId)
                    ->where('sku', $row['sku'])
                    ->first();

                if (!$product) {
                    continue; // Skip if product not found
                }

                // Find or create Location
                $location = StockLocation::firstOrCreate(
                    [
                        'tenant_id' => $this->tenantId,
                        'name' => $row['location']
                    ],
                    ['is_active' => true]
                );

                // Find or create Bin (optional)
                $binId = null;
                if (!empty($row['bin'])) {
                    $bin = StockBin::firstOrCreate(
                        [
                            'tenant_id' => $this->tenantId,
                            'location_id' => $location->id,
                            'name' => $row['bin']
                        ],
                        ['is_active' => true]
                    );
                    $binId = $bin->id;
                }

                // Update or create StockBalance
                $balance = StockBalance::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'location_id' => $location->id,
                        'bin_id' => $binId,
                    ],
                    [
                        'quantity_on_hand' => 0,
                        'quantity_reserved' => 0,
                        'quantity_in_transit' => 0,
                        'quantity_damaged' => 0,
                        'quantity_returned' => 0,
                    ]
                );

                $importedQty = (float)($row['quantity'] ?? 0);
                
                if ($importedQty > 0) {
                    $oldQty = $balance->quantity_on_hand;
                    $newQty = $oldQty + $importedQty;

                    // Log movement
                    StockMovement::create([
                        'tenant_id' => $this->tenantId,
                        'product_id' => $product->id,
                        'location_id' => $location->id,
                        'movement_type_id' => $movementType->id,
                        'movement_type' => 'initial_import',
                        'quantity' => $importedQty,
                        'previous_balance' => $oldQty,
                        'new_balance' => $newQty,
                        'notes' => 'Imported from file',
                        'created_by' => Auth::id(),
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                    ]);

                    $balance->update(['quantity_on_hand' => $newQty]);
                }
            }
        });
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string',
            'location' => 'required|string',
            'quantity' => 'required|numeric|min:0',
        ];
    }
}
