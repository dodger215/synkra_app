<x-layouts.app title="Receive Stock">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:720px;margin:0 auto;">
            <a href="{{ route('product_service.stocks.movements') }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:1rem;"><i class="fa-solid fa-arrow-left"></i> Back to Movements</a>
            <h1 style="color:var(--headings);margin:0 0 .4rem 0;">Receive Stock</h1>
            <p style="color:var(--text-secondary);margin:0 0 1.5rem 0;">Add incoming stock to a location.</p>
            @if($errors->any())<x-ui.alert type="danger" title="Error" :message="$errors->first()" style="margin-bottom:1.5rem;" />@endif
            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                <form action="{{ route('product_service.stocks.receive.store') }}" method="POST">
                    @csrf
                    @include('product_service.stocks.partials.stock-form-fields', ['products' => $products, 'locations' => $locations, 'formType' => 'receive'])
                    <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:.75rem;">
                        <a href="{{ route('product_service.stocks.movements') }}" style="padding:.65rem 1.25rem;border:1px solid var(--border);border-radius:8px;text-decoration:none;color:var(--text-secondary);">Cancel</a>
                        <button type="submit" style="background:var(--primary);border:none;color:white;padding:.65rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;">Receive Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
