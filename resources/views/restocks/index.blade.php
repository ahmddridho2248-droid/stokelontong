<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Restock Queue (Linked List FIFO)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form Enqueue -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 bg-white border-b border-gray-100">
                    <h3 class="text-xl font-extrabold mb-6 text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambahkan Barang ke Antrean
                    </h3>
                    <form action="{{ route('restocks.store') }}" method="POST" class="flex items-end gap-6">
                        @csrf
                        <div class="flex-1">
                            <label for="product_id" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Produk</label>
                            <select name="product_id" id="product_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-200">
                                <option value="" disabled selected>Pilih produk...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-40">
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="quantity" id="quantity" min="1" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-200">
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transform transition hover:-translate-y-0.5">
                                Enqueue (Masuk Antrean)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Visualization Queue -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 bg-gray-50 border-b border-gray-100">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-xl font-extrabold text-gray-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Visualisasi Antrean (FIFO)
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Antrean diproses berdasarkan Linked List pointer pada database.</p>
                        </div>
                        @if($queue->isNotEmpty())
                        <form action="{{ route('restocks.process') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform transition hover:scale-105 hover:shadow-green-500/50 animate-pulse">
                                Proses / Beli Barang Pertama
                            </button>
                        </form>
                        @else
                            <button disabled class="bg-gray-300 text-gray-500 font-bold py-3 px-8 rounded-xl shadow cursor-not-allowed">
                                Antrean Kosong
                            </button>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 relative">
                        @forelse($queue as $index => $node)
                            <div class="flex items-stretch gap-6 relative z-10 group">
                                <div class="w-20 flex flex-col items-center justify-center text-gray-400 font-mono font-bold text-xs uppercase tracking-wider relative">
                                    @if($loop->first)
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full border border-green-200">HEAD</span>
                                    @elseif($loop->last)
                                        <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full border border-gray-300">TAIL</span>
                                    @else
                                        <span>Node {{ $loop->index + 1 }}</span>
                                    @endif
                                    
                                    @if(!$loop->last)
                                        <!-- Vertical line connecting nodes -->
                                        <div class="absolute h-full w-0.5 bg-gray-300 top-1/2 mt-4 -z-10 group-hover:bg-indigo-300 transition-colors duration-300"></div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 {{ $loop->first ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-xl shadow-green-500/30 ring-4 ring-green-500/20 transform scale-[1.02]' : 'bg-white border-gray-200 text-gray-800 hover:border-indigo-300 hover:shadow-md' }} border rounded-2xl p-5 transition-all duration-300">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-full {{ $loop->first ? 'bg-white/20' : 'bg-indigo-50' }} flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $loop->first ? 'text-white' : 'text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-extrabold text-xl">{{ $node->product->name }}</p>
                                                <p class="{{ $loop->first ? 'text-green-50' : 'text-gray-500' }} font-medium text-sm mt-0.5">Kuantitas: {{ $node->quantity }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right text-xs font-mono {{ $loop->first ? 'text-green-100' : 'text-gray-400' }} space-y-1">
                                            <div><span class="opacity-75">ID:</span> <span class="font-bold">{{ $node->id }}</span></div>
                                            <div><span class="opacity-75">Next ID:</span> <span class="font-bold">{{ $node->next_node_id ?? 'NULL' }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="text-gray-500 font-medium">Antrean saat ini kosong.</p>
                                <p class="text-sm text-gray-400 mt-1">Tambahkan barang baru untuk memulai Linked List.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
