<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard view.
     */
    public function index(Request $request)
    {
        return view('dashboard.index');
    }

    /**
     * Fetch realtime metrics for the dashboard via JSON endpoint.
     */
    public function metrics(Request $request)
    {
        $tenant = $request->user()->tenant;
        
        if (!$tenant) {
            return response()->json(['error' => 'No active workspace found for user.'], 403);
        }

        // Aggregate key metrics safely using Eloquent relations
        $totalUsers = $tenant->users()->count();
        $totalProducts = $tenant->products()->count();
        $totalPosOrders = $tenant->posOrders()->count();
        $totalEcommerceOrders = $tenant->ecommerceOrders()->count();
        $totalOrders = $totalPosOrders + $totalEcommerceOrders;
        $transactionsCount = $tenant->transactions()->count();

        return response()->json([
            'metrics' => [
                'users_count' => $totalUsers,
                'products_count' => $totalProducts,
                'orders_count' => $totalOrders,
                'pos_orders' => $totalPosOrders,
                'ecommerce_orders' => $totalEcommerceOrders,
                'transactions_count' => $transactionsCount,
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
