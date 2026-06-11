<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Restock Queue') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 sticky top-6">
                        <h3 class="text-lg font-bold mb-6 text-gray-800 border-b pb-2">Tambah ke Antrean</h3>
                        <form action="{{ route('restocks.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                                <select name="product_id" id="product_id" required class="block w-full rounded-md border-gray-300 py-2.5 px-3 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150 ease-in-out">
                                    <option value="" disabled selected>Pilih produk...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity (Jumlah)</label>
                                <input type="number" name="quantity" id="quantity" min="1" required class="block w-full rounded-md border-gray-300 py-2.5 px-3 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150 ease-in-out">
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambahkan Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Queue Visualization -->
                <div class="lg:col-span-2">
                    <div class="bg-transparent">
                        <h3 class="text-xl font-bold mb-6 text-gray-800">Visualisasi Linked List (FIFO)</h3>
                        
                        <div class="space-y-4">
                            @forelse($queue as $index => $node)
                                
                                @if($loop->first)
                                    <!-- Head Node (Urutan Pertama) -->
                                    <div class="bg-white rounded-xl shadow-md border-l-4 border-blue-500 p-6 relative overflow-hidden transform transition-all duration-300 hover:shadow-lg">
                                        <div class="absolute top-0 right-0 bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                                            Siap Diproses
                                        </div>
                                        
                                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                            <div class="flex items-start gap-4">
                                                <div class="bg-blue-50 rounded-full p-3 mt-1 shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-sm">HEAD</span>
                                                        <p class="text-sm text-gray-500 font-mono">ID: {{ $node->id }}</p>
                                                    </div>
                                                    <h4 class="text-2xl font-bold text-gray-900">{{ $node->product->name }}</h4>
                                                    <p class="text-gray-600 text-lg mt-1">Kuantitas: <span class="font-bold">{{ $node->quantity }}</span></p>
                                                    <p class="text-xs text-gray-400 mt-2 font-mono">Next Pointer: {{ $node->next_node_id ? 'Node ' . $node->next_node_id : 'NULL' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Process Button Area -->
                                        <div class="mt-6 border-t border-blue-100 pt-5">
                                            <form action="{{ route('restocks.process') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center justify-center py-3.5 px-6 rounded-lg shadow-md text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all duration-200 transform hover:-translate-y-0.5" style="background-color: #2563eb;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Proses & Selesaikan Barang Ini
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <!-- Connector Arrow -->
                                    <div class="flex justify-center -my-3 opacity-60 relative z-10 pointer-events-none">
                                        <div class="bg-gray-100 rounded-full p-1 border border-gray-200 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Subsequent Nodes -->
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 opacity-90 hover:opacity-100 transition-opacity duration-200 ml-0 sm:ml-8 relative">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            <div class="flex items-center gap-4">
                                                <div class="text-gray-300 font-bold text-xl w-6 text-center shrink-0">
                                                    {{ $loop->index + 1 }}
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-800">{{ $node->product->name }}</h4>
                                                    <p class="text-sm text-gray-500">Kuantitas: <span class="font-bold">{{ $node->quantity }}</span></p>
                                                </div>
                                            </div>
                                            <div class="sm:text-right flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2 sm:gap-0 mt-2 sm:mt-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-50">
                                                @if($loop->last)
                                                    <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-sm sm:mb-1 block">TAIL</span>
                                                @endif
                                                <div class="flex sm:flex-col gap-3 sm:gap-0">
                                                    <p class="text-[11px] text-gray-400 font-mono">ID: {{ $node->id }}</p>
                                                    <p class="text-[11px] text-gray-400 font-mono">Next: {{ $node->next_node_id ?? 'NULL' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                            @empty
                                <!-- Empty State -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-dashed p-12 text-center flex flex-col items-center justify-center min-h-[300px]">
                                    <div class="bg-gray-50 rounded-full p-4 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-700 mb-2">Antrean Kosong</h4>
                                    <p class="text-gray-500 max-w-sm mx-auto text-sm">
                                        Antrean restock saat ini kosong. Silakan gunakan form di sebelah kiri untuk mulai menambahkan barang ke dalam antrean Linked List.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
