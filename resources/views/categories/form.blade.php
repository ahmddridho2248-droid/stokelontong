<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ isset($category) ? __('Edit Kategori') : __('Tambah Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text-primary">

                    <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
                        @csrf
                        @if (isset($category))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-text-primary mb-1">Nama Kategori</label>
                            <input type="text" name="name" id="name" 
                                value="{{ old('name', $category->name ?? '') }}"
                                class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full" 
                                required autofocus>
                            @error('name')
                                <p class="text-accent text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block font-medium text-sm text-text-primary mb-1">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" rows="3"
                                class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block w-full">{{ old('description', $category->description ?? '') }}</textarea>
                            @error('description')
                                <p class="text-accent text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('categories.index') }}" class="mr-3 text-gray-600 hover:text-gray-900 transition">Batal</a>
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded shadow hover:opacity-90 transition font-medium">
                                {{ isset($category) ? 'Simpan Perubahan' : 'Simpan Kategori' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
