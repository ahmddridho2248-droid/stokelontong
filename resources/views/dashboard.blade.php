<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Section 1: Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-primary">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-1">Total Produk</div>
                        <div class="text-3xl font-bold text-text-primary">{{ number_format($totalProducts) }}</div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-primary">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-1">Total Kategori</div>
                        <div class="text-3xl font-bold text-text-primary">{{ number_format($totalCategories) }}</div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-primary">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-1">Transaksi Hari Ini</div>
                        <div class="text-3xl font-bold text-text-primary">{{ number_format($todayTransactions) }}</div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Low Stock Alert -->
            @if($lowStockProducts->count() > 0)
                <div class="bg-accent/20 border border-accent text-text-primary px-6 py-4 rounded-lg shadow-sm">
                    <div class="flex items-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="font-bold text-lg">Peringatan: Stok Menipis!</h3>
                    </div>
                    <p class="mb-3 text-sm">Produk-produk berikut telah mencapai atau berada di bawah batas minimum stok. Segera lakukan restock.</p>
                    <ul class="list-disc list-inside space-y-1 text-sm bg-white/50 p-3 rounded">
                        @foreach($lowStockProducts as $product)
                            <li>
                                <span class="font-semibold">{{ $product->name }}</span> (SKU: {{ $product->sku }}) - 
                                Tersisa <span class="font-bold text-red-600">{{ $product->stock }}</span> 
                                (Batas: {{ $product->min_stock }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Section 3: Tabel Transaksi Terakhir -->
            <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-text-primary mb-4">5 Transaksi Terakhir</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-background text-text-primary border-b border-gray-200">
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Tipe</th>
                                    <th class="py-3 px-4">Catatan</th>
                                    <th class="py-3 px-4">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                                        <td class="py-3 px-4">
                                            @if($transaction->type === 'in')
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-semibold border border-green-200">Masuk</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-semibold border border-red-200">Keluar</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">{{ $transaction->notes ?? '-' }}</td>
                                        <td class="py-3 px-4">{{ $transaction->user->name ?? 'Unknown' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
