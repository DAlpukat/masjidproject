@extends('layouts.app')

@section('content')
<!-- TAMBAHKAN BACKGROUND DI SINI AGAR TEMA MASUK -->
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-10 relative z-10">
    
    <!-- Header Dashboard -->
    <div class="mb-8 border-b border-white/10 pb-4">
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Superadmin</span> Panel
        </h1>
        <p class="text-gray-400 mt-1">Kelola persetujuan kelas dan manajemen pengguna global.</p>
    </div>

    <!-- Alert Messages (Dark Glass Style) -->
    @if(session('success'))
        <div class="mb-6 glass-card border-l-4 border-green-500 p-4 rounded-lg relative overflow-hidden">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-green-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-100 font-medium">{!! session('success') !!}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 glass-card border-l-4 border-red-500 p-4 rounded-lg relative overflow-hidden">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-red-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-100 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- KOLOM KIRI: Persetujuan Kelas (Glass Card) -->
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col">
            <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                <div>
                    <h2 class="text-lg font-bold text-white">Kelas Menunggu Persetujuan</h2>
                    <p class="text-xs text-gray-400 mt-1">Status: Pending</p>
                </div>
                <span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-xs px-3 py-1 rounded-full font-bold">
                    {{ $pendingTempats->count() }} Pending
                </span>
            </div>
            
            <div class="p-6 flex-grow">
                @if($pendingTempats->count() > 0)
                    <ul class="space-y-4">
                        @foreach($pendingTempats as $item)
                        <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-white/5 pb-4 last:border-0 last:pb-0 gap-3">
                            <div class="flex-1">
                                <div class="font-bold text-white">{{ $item->nama }}</div>
                                <div class="text-sm text-gray-400">Pembuat: <span class="font-medium text-gray-200">{{ $item->user->name ?? 'Unknown' }}</span></div>
                                <div class="flex gap-2 mt-2">
                                    @if($item->is_public)
                                        <span class="text-[10px] bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded border border-blue-500/30">Publik</span>
                                    @else
                                        <span class="text-[10px] bg-red-500/20 text-red-300 px-2 py-0.5 rounded border border-red-500/30">Privat</span>
                                    @endif
                                    @if($item->kode_referral)
                                        <span class="text-[10px] bg-gray-700 text-gray-300 px-2 py-0.5 rounded border border-gray-600 font-mono">Kode: {{ $item->kode_referral }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex gap-2 w-full sm:w-auto">
                                <!-- Form Approve -->
                                <form action="{{ route('superadmin.approve', $item->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-green-600/80 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-lg shadow-green-900/20">
                                        ✓ Aktifkan
                                    </button>
                                </form>
                                
                                <!-- Form Delete Tempat -->
                                <form action="{{ route('superadmin.destroy.tempat', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full sm:w-auto bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 hover:text-red-300 px-3 py-2 rounded-lg text-sm font-medium transition">
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-sm">Tidak ada kelas yang menunggu persetujuan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Manajemen User (Glass Card) -->
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col">
            <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                <div>
                    <h2 class="text-lg font-bold text-white">Manajemen User</h2>
                    <p class="text-xs text-gray-400 mt-1">Total User: {{ $users->count() }}</p>
                </div>
            </div>
            <div class="p-0 overflow-x-auto flex-grow">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-black/20 font-medium text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $user)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-3">
                                <div class="font-medium text-white">{{ $user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-3">
                                @if($user->is_superadmin ?? false)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                        Superadmin
                                    </span>
                                @elseif($user->is_admin ?? false)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right">
                                @if(!($user->is_superadmin ?? false))
                                    <form action="{{ route('superadmin.destroy.user', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-bold uppercase tracking-wide hover:bg-red-500/10 px-2 py-1 rounded transition">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-600 text-xs font-bold uppercase cursor-not-allowed">Locked</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- SECTION BARU: DAFTAR SEMUA KELAS (Glass Card) -->
    <div class="mt-8 glass-card rounded-2xl overflow-hidden">
        <div class="p-6 bg-white/5 border-b border-white/10 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-white">Daftar Semua Kelas</h2>
                <p class="text-xs text-gray-400 mt-1">Total Kelas: {{ $allTempats->count() }}</p>
            </div>
        </div>
        
        <div class="p-0 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-black/20 font-medium text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Nama Kelas</th>
                        <th class="px-6 py-3">Pemilik</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($allTempats as $item)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-white">{{ $item->nama }}</div>
                            <div class="text-xs text-gray-500">ID: #{{ $item->id }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            {{ $item->user->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_public)
                                <span class="text-[10px] bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded border border-blue-500/30">Publik</span>
                            @else
                                <span class="text-[10px] bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded border border-indigo-500/30">Privat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                                    Aktif
                                </span>
                            @elseif($item->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">
                                    {{ $item->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('superadmin.destroy.tempat', $item->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Hapus kelas {{ $item->nama }} permanen?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 hover:bg-red-500/10 px-3 py-1 rounded text-xs font-bold border border-transparent hover:border-red-500/30 transition">
                                    Hapus Permanen
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
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