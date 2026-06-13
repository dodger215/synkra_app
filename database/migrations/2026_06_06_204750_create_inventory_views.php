<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS stock_alerts;");
        DB::statement("
            CREATE VIEW stock_alerts AS
            SELECT 
                p.id AS product_id,
                p.name AS product_name,
                sb.location_id,
                sl.name AS location_name,
                sb.quantity_on_hand,
                p.reorder_point,
                p.min_stock_level,
                CASE 
                    WHEN sb.quantity_on_hand <= p.reorder_point THEN 'CRITICAL - Reorder Now'
                    WHEN sb.quantity_on_hand <= p.min_stock_level THEN 'LOW - Restock Soon'
                    ELSE 'OK'
                END AS alert_level
            FROM stock_balances sb
            JOIN products p ON sb.product_id = p.id
            JOIN stock_locations sl ON sb.location_id = sl.id
            WHERE sb.quantity_on_hand <= p.reorder_point OR sb.quantity_on_hand <= p.min_stock_level;
        ");

        DB::statement("DROP VIEW IF EXISTS inventory_valuation;");
        DB::statement("
            CREATE VIEW inventory_valuation AS
            SELECT 
                p.id AS product_id,
                p.name,
                sb.location_id,
                sb.quantity_on_hand,
                p.cost_price,
                (sb.quantity_on_hand * p.cost_price) AS total_value
            FROM stock_balances sb
            JOIN products p ON sb.product_id = p.id
            WHERE sb.quantity_on_hand > 0;
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS stock_alerts;");
        DB::statement("DROP VIEW IF EXISTS inventory_valuation;");
    }
};
