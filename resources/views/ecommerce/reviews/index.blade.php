<x-layouts.app title="Product Reviews - {{ $store->store_name }}">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                    <a href="{{ route('ecommerce.stores.index') }}" style="color: inherit; text-decoration: none;">Ecommerce</a>
                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                    <a href="{{ route('ecommerce.stores.show', $store->id) }}" style="color: inherit; text-decoration: none;">{{ $store->store_name }}</a>
                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                    <span style="color: var(--primary); font-weight: 600;">Product Reviews</span>
                </nav>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Product Reviews</h1>
            </div>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 1.5rem;" />
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; border: 1px solid var(--border); overflow: hidden;">
            @if($reviews->isEmpty())
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Reviews Yet</h3>
                    <p style="color: var(--text-secondary); margin: 0;">Customer reviews for your products will appear here.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border); background: var(--surface-secondary);">
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Product</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Customer</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Rating</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Review</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Status</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Date</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='var(--surface-secondary)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="font-weight: 600; color: var(--text-primary);">{{ $review->product->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">SKU: {{ $review->product->sku }}</div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="font-weight: 500; color: var(--text-primary);">{{ $review->customer->name ?? 'Anonymous' }}</div>
                                        @if($review->is_verified_purchase)
                                            <div style="font-size: 0.7rem; color: #166534; font-weight: 600;">
                                                <i class="fa-solid fa-circle-check"></i> Verified Purchase
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="color: #f59e0b; display: flex; gap: 2px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $review->title }}</div>
                                        <div style="font-size: 0.875rem; color: var(--text-secondary); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $review->comment }}">
                                            {{ $review->comment }}
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <span style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600;
                                            @if($review->status === 'approved') background: #dcfce7; color: #166534;
                                            @elseif($review->status === 'pending') background: #fef9c3; color: #854d0e;
                                            @else background: #fee2e2; color: #991b1b; @endif">
                                            {{ ucfirst($review->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; color: var(--text-secondary); font-size: 0.875rem;">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="display: flex; gap: 0.5rem;">
                                            @if($review->status !== 'approved')
                                                <form action="{{ route('ecommerce.reviews.approve', [$store->id, $review->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" style="background: none; border: none; color: #166534; cursor: pointer; font-weight: 600; font-size: 0.875rem;">Approve</button>
                                                </form>
                                            @endif
                                            @if($review->status !== 'rejected')
                                                <form action="{{ route('ecommerce.reviews.reject', [$store->id, $review->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" style="background: none; border: none; color: #991b1b; cursor: pointer; font-weight: 600; font-size: 0.875rem;">Reject</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('ecommerce.reviews.destroy', [$store->id, $review->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.875rem;"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
