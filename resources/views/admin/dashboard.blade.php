@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Saya</h1>
            
            <!-- Tombol Buat Tempat Baru -->
            <a href="{{ route('admin.temp.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                + Buat Tempat Layanan Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {!! session('success') !!}
            </div>
        @endif

        <!-- Tabel Data -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Tempat
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Akses / Kode Referral
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jumlah Anggota
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tanggal Dibuat
                        </th>
                        <!-- Kolom Aksi Baru -->
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tempatLayanans as $item)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $item->nama }}</p>
                                <p class="text-gray-600 text-xs">{{ $item->slug }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @if($item->status == 'aktif')
                                    <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                        <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                        <span class="relative">Aktif</span>
                                    </span>
                                @else
                                    <span class="relative inline-block px-3 py-1 font-semibold text-yellow-900 leading-tight">
                                        <span aria-hidden class="absolute inset-0 bg-yellow-200 opacity-50 rounded-full"></span>
                                        <span class="relative">Pending</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @if($item->is_public)
                                    <span class="text-blue-600">Terbuka (Publik)</span>
                                @else
                                    <div class="flex items-center">
                                        <span class="text-red-600 font-mono font-bold mr-2">{{ $item->kode_referral }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ $item->kode_referral }}'); alert('Disalin!')" class="text-xs bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">
                                            Copy
                                        </button>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $item->users->count() }} Orang
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ $item->created_at->format('d M Y') }}
                                </p>
                            </td>
                            <!-- Tombol Kelola Halaman Baru -->
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('pages.index', $item->id) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-2">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <!-- colspan diubah dari 5 jadi 6 karena ada kolom Aksi -->
                        <tr>
                            <td colspan="6" class="px-5 py-10 border-b border-gray-200 bg-white text-center text-gray-500">
                                Kamu belum membuat tempat layanan apapun. Klik tombol di atas untuk membuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection