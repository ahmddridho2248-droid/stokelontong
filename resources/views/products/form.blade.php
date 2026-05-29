<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ isset($product) ? __('Edit Produk') : __('Tambah Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text-primary">

                    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST">
                        @csrf
                        @if (isset($product))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="sku" class="block font-medium text-sm text-text-primary mb-1">SKU</label>
                                <input type="text" name="sku" id="sku" 
                                    value="{{ old('sku', $product->sku ?? '') }}"
                                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" 
                                    required>
                                @error('sku')
                                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name" class="block font-medium text-sm text-text-primary mb-1">Nama Produk</label>
                                <input type="text" name="name" id="name" 
                                    value="{{ old('name', $product->name ?? '') }}"
                                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" 
                                    required autofocus>
                                @error('name')
                                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 col-span-1 md:col-span-2">
                                <label for="category_id" class="block font-medium text-sm text-text-primary mb-1">Kategori</label>
                                <select name="category_id" id="category_id" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" required>
                                    <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="purchase_price" class="block font-medium text-sm text-text-primary mb-1">Harga Beli</label>
                                <input type="number" name="purchase_price" id="purchase_price" 
                                    value="{{ old('purchase_price', isset($product) ? (int)$product->purchase_price : '') }}"
                                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" 
                                    required min="0">
                                @error('purchase_price')
                                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="selling_price" class="block font-medium text-sm text-text-primary mb-1">Harga Jual</label>
                                <input type="number" name="selling_price" id="selling_price" 
                                    value="{{ old('selling_price', isset($product) ? (int)$product->selling_price : '') }}"
                                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" 
                                    required min="0">
                                @error('selling_price')
                                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="stock" class="block font-medium text-sm text-text-primary mb-1">Stok Awal</label>
                                <input type="number" name="stock" id="stock" 
                                    value="{{ old('stock', $product->stock ?? 0) }}"
                                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" 
                                    required min="0">
                                @error('stock')
                                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="min_stock" class="block font-medium text-sm text-text-primary mb-1">Batas Minimum Stok</label>
                                <input type="number" name="min_stock" id="min_stock" 
                                    value="{{ old('min_stock', $product->min_stock ?? 0) }}"
                                    class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" 
                                    required min="0">
                                @error('min_stock')
                                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('products.index') }}" class="mr-3 text-gray-600 hover:text-gray-900 transition">Batal</a>
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded shadow hover:opacity-90 transition font-medium">
                                {{ isset($product) ? 'Simpan Perubahan' : 'Simpan Produk' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
