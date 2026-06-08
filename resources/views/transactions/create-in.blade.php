<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ __('Barang Masuk (Restock / Inbound)') }}
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

                    <form action="{{ route('transactions.in.store') }}" method="POST">
                        @csrf

                        <!-- Keterangan Transaksi -->
                        <div class="mb-6">
                            <label for="notes" class="block font-medium text-sm text-text-primary mb-1">Catatan / Keterangan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full"
                                placeholder="Contoh: Pembelian dari Supplier ABC, Restock Mingguan">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-accent text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Daftar Barang Dinamis -->
                        <div class="mb-2">
                            <label class="block font-medium text-sm text-text-primary">Daftar Barang Masuk</label>
                            <p class="text-xs text-gray-500 mb-2">Pilih barang dan jumlah kuantitas yang baru masuk.</p>
                        </div>
                        
                        <div id="product-rows-container" class="space-y-4">
                            <!-- Row Pertama (Default) -->
                            <div class="flex items-center space-x-4 product-row">
                                <div class="flex-grow flex items-center space-x-2">
                                    <select name="products[0][id]" class="product-select border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" required>
                                        <option value="" disabled selected>-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} (SKU: {{ $product->sku }} | Stok Saat Ini: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn-new-product whitespace-nowrap bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-2 rounded border border-gray-300 shadow-sm transition">
                                        + Produk Baru
                                    </button>
                                </div>
                                <div class="w-32">
                                    <input type="number" name="products[0][quantity]" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" placeholder="Qty" required min="1">
                                </div>
                                <div class="w-10 text-center">
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
                                Simpan Transaksi Masuk
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Tailwind Modal untuk Produk Baru -->
    <div id="newProductModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Tambah Produk Baru</h3>
            <form id="newProductForm">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" name="name" id="modal-name" class="border-gray-300 rounded-md shadow-sm w-full" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" id="modal-category" class="border-gray-300 rounded-md shadow-sm w-full" required>
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                    <input type="number" name="purchase_price" id="modal-purchase-price" class="border-gray-300 rounded-md shadow-sm w-full" required min="0">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                    <input type="number" name="selling_price" id="modal-selling-price" class="border-gray-300 rounded-md shadow-sm w-full" required min="0">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="btn-close-modal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">Batal</button>
                    <button type="submit" id="btn-save-modal" class="px-4 py-2 bg-primary text-white rounded hover:bg-teal-700 transition flex items-center">
                        <span id="modal-spinner" class="hidden mr-2 w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('product-rows-container');
            const btnAdd = document.getElementById('btn-add-row');
            
            // Modal Elements
            const modal = document.getElementById('newProductModal');
            const formModal = document.getElementById('newProductForm');
            const btnCloseModal = document.getElementById('btn-close-modal');
            const btnSaveModal = document.getElementById('btn-save-modal');
            const modalSpinner = document.getElementById('modal-spinner');

            let rowIdx = 0; // Index starts at 0
            let targetSelect = null; // To remember which select triggered the modal

            // Tambah baris baru
            btnAdd.addEventListener('click', function () {
                rowIdx++;
                
                const firstRow = container.querySelector('.product-row');
                const newRow = firstRow.cloneNode(true);
                
                const select = newRow.querySelector('.product-select');
                select.name = `products[${rowIdx}][id]`;
                select.value = '';
                
                const input = newRow.querySelector('input[type="number"]');
                input.name = `products[${rowIdx}][quantity]`;
                input.value = '';
                
                const btnRemove = newRow.querySelector('.btn-remove-row');
                btnRemove.classList.remove('hidden');
                
                btnRemove.addEventListener('click', function () {
                    newRow.remove();
                });

                // Attach event listener to new button
                const btnNewProduct = newRow.querySelector('.btn-new-product');
                btnNewProduct.addEventListener('click', function () {
                    openModal(select);
                });
                
                container.appendChild(newRow);
            });

            // Inisialisasi event listener untuk row pertama
            const initialBtnNewProduct = container.querySelector('.btn-new-product');
            const initialSelect = container.querySelector('.product-select');
            initialBtnNewProduct.addEventListener('click', function () {
                openModal(initialSelect);
            });

            function openModal(selectElement) {
                targetSelect = selectElement;
                formModal.reset();
                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                targetSelect = null;
            }

            btnCloseModal.addEventListener('click', closeModal);

            // Close modal when clicking outside
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Handle AJAX Submit
            formModal.addEventListener('submit', function (e) {
                e.preventDefault();

                // Show spinner, disable button
                btnSaveModal.disabled = true;
                modalSpinner.classList.remove('hidden');

                const formData = new FormData(formModal);
                
                fetch("{{ route('transactions.api.products.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const newProduct = data.product;
                        const optionText = `${newProduct.name} (SKU: ${newProduct.sku} | Stok Saat Ini: ${newProduct.stock})`;
                        const optionValue = newProduct.id;

                        // Tambahkan option baru ke semua select dengan class product-select
                        const allSelects = document.querySelectorAll('.product-select');
                        allSelects.forEach(select => {
                            const option = new Option(optionText, optionValue);
                            select.add(option);
                        });

                        // Pilih option baru pada select yang memicu modal
                        if (targetSelect) {
                            targetSelect.value = optionValue;
                        }

                        closeModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errMsg = 'Terjadi kesalahan saat menyimpan produk. Periksa kembali input Anda.';
                    if (error.errors) {
                        errMsg = Object.values(error.errors).flat().join('\n');
                    } else if (error.message) {
                        errMsg = error.message;
                    }
                    alert(errMsg);
                })
                .finally(() => {
                    btnSaveModal.disabled = false;
                    modalSpinner.classList.add('hidden');
                });
            });
        });
    </script>
</x-app-layout>
