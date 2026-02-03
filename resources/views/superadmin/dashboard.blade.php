@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-10">
    
    <div class="mb-6 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-gray-900">
            <span class="text-purple-600">Superadmin</span> Panel
        </h1>
        <p class="text-gray-500 mt-1">Kelola persetujuan kelas dan manajemen pengguna global.</p>
    </div>

    <!-- Alert Message (Optional) -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {!! session('success') !!}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- KOLOM KIRI: Persetujuan Kelas -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Kelas Menunggu Persetujuan</h2>
                    <p class="text-xs text-gray-500 mt-1">Status: Pending</p>
                </div>
                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-bold">
                    {{ $pendingTempats->count() }} Pending
                </span>
            </div>
            
            <div class="p-6">
                @if($pendingTempats->count() > 0)
                    <ul class="space-y-4">
                        @foreach($pendingTempats as $item)
                        <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-4 last:border-0 gap-3">
                            <div class="flex-1">
                                <div class="font-bold text-gray-800">{{ $item->nama }}</div>
                                <div class="text-sm text-gray-500">Pembuat: <span class="font-medium text-gray-700">{{ $item->user->name ?? 'Unknown' }}</span></div>
                                <div class="flex gap-2 mt-2">
                                    @if($item->is_public)
                                        <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100">Publik</span>
                                    @else
                                        <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded border border-red-100">Privat</span>
                                    @endif
                                    @if($item->kode_referral)
                                        <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded border border-gray-200 font-mono">Kode: {{ $item->kode_referral }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex gap-2 w-full sm:w-auto">
                                <!-- Form Approve -->
                                <form action="{{ route('superadmin.approve', $item->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium transition shadow-sm">
                                        ✓ Aktifkan
                                    </button>
                                </form>
                                
                                <!-- Form Delete Tempat -->
                                <form action="{{ route('superadmin.destroy.tempat', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini secara permanen? Data kas dan postingan akan hilang.');" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full sm:w-auto bg-white border border-red-200 text-red-600 hover:bg-red-50 px-3 py-2 rounded text-sm font-medium transition shadow-sm">
                                        🗑 Hapus
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm">Tidak ada kelas yang menunggu persetujuan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Manajemen User -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Manajemen User</h2>
                    <p class="text-xs text-gray-500 mt-1">Total User: {{ $users->count() }}</p>
                </div>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 font-medium text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-3">
                                @if($user->is_superadmin ?? false)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Superadmin
                                    </span>
                                @elseif($user->is_admin ?? false)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right">
                                @if(!($user->is_superadmin ?? false))
                                    <form action="{{ route('superadmin.destroy.user', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-bold uppercase tracking-wide">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-300 text-xs font-bold uppercase cursor-not-allowed">Locked</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- SECTION BARU: DAFTAR SEMUA KELAS -->
    <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="p-6 bg-gray-800 border-b border-gray-700 flex justify-between items-center text-white">
            <div>
                <h2 class="text-lg font-bold">Daftar Semua Kelas</h2>
                <p class="text-xs text-gray-300 mt-1">Total Kelas: {{ $allTempats->count() }}</p>
            </div>
        </div>
        
        <div class="p-0 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-50 font-medium text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Nama Kelas</th>
                        <th class="px-6 py-3">Pemilik</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($allTempats as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $item->nama }}</div>
                            <div class="text-xs text-gray-400">ID: #{{ $item->id }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $item->user->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_public)
                                <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100">Publik</span>
                            @else
                                <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100">Privat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @elseif($item->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $item->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('superadmin.destroy.tempat', $item->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Hapus kelas {{ $item->nama }} permanen?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 px-3 py-1 rounded text-xs font-bold border border-transparent hover:border-red-200 transition">
                                    Hapus Permanen
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            Belum ada data kelas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection