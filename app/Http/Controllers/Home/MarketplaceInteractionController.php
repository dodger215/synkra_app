<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\ProductLike;
use App\Models\TenantFollow;
use App\Models\ProductReview;
use App\Models\FeedsModel;
use Illuminate\Support\Facades\Auth;

class MarketplaceInteractionController extends Controller
{
    public function toggleLike(Product $product)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $like = ProductLike::where('customer_id', $customer->id)
            ->where('product_id', $product->id)
            ->first();

        if ($like) {
            $like->delete();

            // Update Feed
            $feed = FeedsModel::firstOrCreate(['tenant_id' => $product->tenant_id]);
            $unliked = $feed->unliked_entities ?? [];
            $unliked[] = [
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'timestamp' => now()->toIso8601String()
            ];
            $feed->update(['unliked_entities' => $unliked]);

            return response()->json(['status' => 'unliked', 'count' => $product->likes()->count()]);
        }

        ProductLike::create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        // Update Feed
        $feed = FeedsModel::firstOrCreate(['tenant_id' => $product->tenant_id]);
        $liked = $feed->liked_entities ?? [];
        $liked[] = [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'timestamp' => now()->toIso8601String()
        ];
        $feed->update(['liked_entities' => $liked]);

        return response()->json(['status' => 'liked', 'count' => $product->likes()->count()]);
    }

    public function toggleFollow(Tenant $tenant)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $follow = TenantFollow::where('customer_id', $customer->id)
            ->where('tenant_id', $tenant->id)
            ->first();

        if ($follow) {
            $follow->delete();

            // Update Feed
            $feed = FeedsModel::firstOrCreate(['tenant_id' => $tenant->id]);
            $unfollowed = $feed->unfollowed_entities ?? [];
            $unfollowed[] = [
                'customer_id' => $customer->id,
                'customer_name' => $customer->first_name . ' ' . $customer->last_name,
                'timestamp' => now()->toIso8601String()
            ];
            $feed->update(['unfollowed_entities' => $unfollowed]);

            return response()->json(['status' => 'unfollowed', 'count' => $tenant->followers()->count()]);
        }

        TenantFollow::create([
            'customer_id' => $customer->id,
            'tenant_id' => $tenant->id,
        ]);

        // Update Feed
        $feed = FeedsModel::firstOrCreate(['tenant_id' => $tenant->id]);
        $followed = $feed->followed_entities ?? [];
        $followed[] = [
            'customer_id' => $customer->id,
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
            'timestamp' => now()->toIso8601String()
        ];
        $feed->update(['followed_entities' => $followed]);

        return response()->json(['status' => 'followed', 'count' => $tenant->followers()->count()]);
    }

    public function storeReview(Request $request, Product $product)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return back()->with('error', 'You must be logged in to leave a review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'product_id' => 'nullable|exists:products,id',
        ]);

        ProductReview::create([
            'product_id' => $request->input('product_id', $product->id),
            'customer_id' => $customer->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Or 'approved' if no moderation is needed
        ]);

        return back()->with('status', 'Thank you for your review! It is pending approval.');
    }
}
