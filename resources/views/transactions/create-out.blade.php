<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ __('Barang Keluar (Terjual / Outbound)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text-primary">

                    @if (session('error'))
                        <div class="mb-4 bg-accent/20 text-accent border border-accent p-4 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('transactions.out.store') }}" method="POST">
                        @csrf

                        <!-- Keterangan Transaksi -->
                        <div class="mb-6">
                            <label for="notes" class="block font-medium text-sm text-text-primary mb-1">Catatan / Keterangan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full"
                                placeholder="Contoh: Penjualan kasir shift 1, atau Retur barang rusak">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-accent text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Daftar Barang Dinamis -->
                        <div class="mb-2">
                            <label class="block font-medium text-sm text-text-primary">Daftar Barang Keluar</label>
                            <p class="text-xs text-gray-500 mb-2">Pastikan kuantitas tidak melebihi sisa stok yang tersedia.</p>
                        </div>
                        
                        <div id="product-rows-container" class="space-y-4">
                            <!-- Row Pertama (Default) -->
                            <div class="flex items-center space-x-4 product-row">
                                <div class="flex-grow">
                                    <select name="products[0][id]" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" required>
                                        <option value="" disabled selected>-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} (SKU: {{ $product->sku }} | Stok: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32">
                                    <input type="number" name="products[0][quantity]" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" placeholder="Qty" required min="1">
                                </div>
                                <div class="w-10 text-center">
                                    <!-- Tombol hapus disembunyikan untuk row pertama -->
                                    <button type="button" class="text-accent hover:text-red-700 font-bold hidden btn-remove-row" title="Hapus Baris">X</button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah Baris -->
                        <div class="mt-4 mb-8">
                            <button type="button" id="btn-add-row" class="text-primary hover:text-teal-700 text-sm font-semibold flex items-center">
                                + Tambah Barang Lain
                            </button>
                        </div>

                        <div class="flex items-center justify-end border-t pt-4">
                            <a href="{{ route('dashboard') }}" class="mr-3 text-gray-600 hover:text-gray-900 transition">Batal</a>
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded shadow hover:opacity-90 transition font-medium">
                                Simpan Transaksi Keluar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Dynamic Rows -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('product-rows-container');
            const btnAdd = document.getElementById('btn-add-row');
            let rowIdx = 0; // Index starts at 0, next will be 1

            btnAdd.addEventListener('click', function () {
                rowIdx++;
                
                // Clone the first row
                const firstRow = container.querySelector('.product-row');
                const newRow = firstRow.cloneNode(true);
                
                // Update names of inputs to have the correct array index
                const select = newRow.querySelector('select');
                select.name = `products[${rowIdx}][id]`;
                select.value = ''; // Reset selection
                
                const input = newRow.querySelector('input');
                input.name = `products[${rowIdx}][quantity]`;
                input.value = ''; // Reset quantity
                
                // Show remove button
                const btnRemove = newRow.querySelector('.btn-remove-row');
                btnRemove.classList.remove('hidden');
                
                // Add event listener to remove button
                btnRemove.addEventListener('click', function () {
                    newRow.remove();
                });
                
                // Append new row to container
                container.appendChild(newRow);
            });
        });
    </script>
</x-app-layout>
