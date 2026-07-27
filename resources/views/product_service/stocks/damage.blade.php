<x-layouts.app title="Report Stock Damage">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:720px;margin:0 auto;">
            <a href="{{ route('product_service.stocks.damages.index') }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:1rem;"><i class="fa-solid fa-arrow-left"></i> Back to Damages</a>
            <h1 style="color:var(--headings);margin:0 0 1.5rem 0;">Report Stock Damage</h1>
            @if($errors->any())<x-ui.alert type="danger" title="Error" :message="$errors->first()" style="margin-bottom:1.5rem;" />@endif
            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                <form action="{{ route('product_service.stocks.damage.store') }}" method="POST">@csrf
                    @include('product_service.stocks.partials.stock-form-fields', ['products' => $products, 'locations' => $locations, 'formType' => 'damage'])
                    <div style="margin-top:1.5rem;text-align:right;"><button type="submit" style="background:var(--primary);border:none;color:white;padding:.65rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;">Report Damage</button></div>
                </form>
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
