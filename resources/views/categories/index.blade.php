<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-primary leading-tight">
            {{ __('Daftar Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text-primary">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-primary/20 text-primary border border-primary p-4 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-accent/20 text-accent border border-accent p-4 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Kategori Barang</h3>
                        <a href="{{ route('categories.create') }}" class="bg-primary text-white px-4 py-2 rounded shadow hover:opacity-90 transition">
                            + Tambah Kategori
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-background text-text-primary border-b border-gray-200">
                                    <th class="py-3 px-4">#</th>
                                    <th class="py-3 px-4">Nama Kategori</th>
                                    <th class="py-3 px-4">Deskripsi</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $index => $category)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $categories->firstItem() + $index }}</td>
                                        <td class="py-3 px-4 font-medium">{{ $category->name }}</td>
                                        <td class="py-3 px-4">{{ $category->description ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                                
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-accent text-white px-3 py-1 rounded shadow hover:opacity-90 transition text-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
