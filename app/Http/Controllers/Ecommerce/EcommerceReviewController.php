<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\EcommerceStore;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EcommerceReviewController extends Controller
{
    public function index(EcommerceStore $store)
    {
        $this->authorizeStore($store);

        $reviews = ProductReview::whereHas('product', function ($query) use ($store) {
            $query->where('tenant_id', $store->tenant_id);
        })
        ->with(['product', 'customer'])
        ->latest()
        ->paginate(20);

        return view('ecommerce.reviews.index', compact('store', 'reviews'));
    }

    public function approve(EcommerceStore $store, ProductReview $review)
    {
        $this->authorizeStore($store);

        // Ensure the review belongs to the tenant's product
        if ($review->product->tenant_id !== $store->tenant_id) {
            abort(403);
        }

        $review->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Review approved successfully.');
    }

    public function reject(EcommerceStore $store, ProductReview $review)
    {
        $this->authorizeStore($store);

        if ($review->product->tenant_id !== $store->tenant_id) {
            abort(403);
        }

        $review->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(EcommerceStore $store, ProductReview $review)
    {
        $this->authorizeStore($store);

        if ($review->product->tenant_id !== $store->tenant_id) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }

    private function authorizeStore(EcommerceStore $store)
    {
        if ($store->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }
}
