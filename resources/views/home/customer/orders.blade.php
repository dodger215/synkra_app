<x-layouts.shop>
    <x-slot:title>Order History</x-slot:title>

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div>
                <h1 class="font-display-lg text-headline-md font-bold text-primary uppercase tracking-tighter">ORDER HISTORY</h1>
                <p class="text-on-surface-variant">View and track your previous orders.</p>
            </div>
            <form action="{{ route('home.customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 border border-outline rounded-lg font-bold text-sm hover:bg-surface-container transition-colors">
                    LOGOUT
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter">
            <!-- Sidebar -->
            <aside class="md:col-span-1">
                <nav class="flex flex-col gap-2">
                    <a href="{{ route('home.customer.dashboard') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.dashboard') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">DASHBOARD</a>
                    <a href="{{ route('home.customer.orders') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.orders') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">ORDER HISTORY</a>
                    <a href="{{ route('home.customer.saved_items') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.saved_items') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">SAVED ITEMS</a>
                    <a href="{{ route('home.customer.settings') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.settings') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">ACCOUNT SETTINGS</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="md:col-span-3">
                <div class="bg-surface-container-lowest border border-surface-container rounded-2xl overflow-hidden">
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-surface-container-low text-on-surface-variant uppercase text-[10px] font-bold tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Order #</th>
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Total</th>
                                        <th class="px-6 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-surface-container">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-surface-container-lowest transition-colors">
                                            <td class="px-6 py-4 font-bold">{{ $order->order_number }}</td>
                                            <td class="px-6 py-4 text-on-surface-variant">{{ $order->ordered_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $order->fulfillment_status === 'delivered' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }}">
                                                    {{ $order->fulfillment_status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-bold">{{ number_format($order->total_amount, 2) }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="#" class="text-primary font-bold hover:underline">Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 border-t border-surface-container">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="p-6 text-center py-12">
                            <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">receipt_long</span>
                            <p class="text-sm text-on-surface-variant">You haven't placed any orders yet.</p>
                            <a href="{{ route('home.index') }}" class="inline-block mt-4 text-primary font-bold text-sm hover:underline">Start Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.shop>
