<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $tenantId;

    public function __construct()
    {
        $this->tenantId = Auth::user()->tenant_id;
    }

    public function model(array $row)
    {
        // Find or create category if name is provided
        $categoryId = null;
        if (!empty($row['category'])) {
            $category = ProductCategory::firstOrCreate(
                [
                    'tenant_id' => $this->tenantId,
                    'name' => $row['category']
                ],
                [
                    'description' => 'Imported category',
                    'is_active' => true
                ]
            );
            $categoryId = $category->id;
        }

        return new Product([
            'tenant_id' => $this->tenantId,
            'sku' => $row['sku'],
            'barcode' => $row['barcode'] ?? null,
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
            'category_id' => $categoryId,
            'brand' => $row['brand'] ?? null,
            'unit_type' => $row['unit'] ?? 'piece',
            'unit_price' => $row['unit_price'] ?? 0,
            'cost_price' => $row['cost_price'] ?? null,
            'weight_kg' => $row['weight_kg'] ?? null,
            'min_stock_level' => $row['min_stock'] ?? 0,
            'max_stock_level' => $row['max_stock'] ?? null,
            'reorder_point' => $row['reorder_point'] ?? 0,
            'reorder_quantity' => $row['reorder_qty'] ?? 0,
            'is_active' => strtolower($row['active'] ?? 'yes') === 'yes' || strtolower($row['active'] ?? '1') === '1',
            'tax_rate' => $row['tax_rate'] ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'unit_price' => 'nullable|numeric|min:0',
        ];
    }
}
