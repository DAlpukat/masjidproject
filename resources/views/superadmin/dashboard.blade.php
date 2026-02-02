@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-10">
    
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Panel Superadmin</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- KOLOM KIRI: Persetujuan Kelas -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Kelas Menunggu Persetujuan (Pending)</h2>
                <p class="text-sm text-gray-500 mt-1">Kelas yang belum diaktifkan tidak bisa diakses user.</p>
            </div>
            <div class="p-6">
                @if(count($pendingTempats) > 0)
                    <ul class="space-y-4">
                        @foreach($pendingTempats as $item)
                        <li class="flex justify-between items-center border-b border-gray-100 pb-4 last:border-0">
                            <div>
                                <div class="font-bold text-lg">{{ $item->nama }}</div>
                                <div class="text-sm text-gray-500">Pembuat: {{ $item->user->name }}</div>
                                <div class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded inline-block mt-1">{{ $item->status }}</div>
                            </div>
                            
                            <form action="{{ route('superadmin.approve', $item->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                                    Aktifkan
                                </button>
                                <a href="{{ route('superadmin.destroy.tempat', $item->id) }}" 
                                   onclick="return confirm('Hapus permanen?');" 
                                   class="text-red-600 hover:text-red-800 font-medium text-sm">
                                   Hapus
                                </a>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-center py-4">Tidak ada kelas pending.</p>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Manajemen User -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Manajemen User</h2>
                <p class="text-sm text-gray-500 mt-1">Hapus user yang tidak diinginkan.</p>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-gray-100 font-medium text-gray-700">
                            <tr>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Role</th>
                                <th class="px-4 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if($user->is_superadmin)
                                        <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-xs font-bold">SUPERADMIN</span>
                                    @elseif($user->is_admin)
                                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">Admin</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">User</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if(!$user->is_superadmin)
                                        <form action="{{ route('superadmin.destroy.user', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin hapus user ini?')" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs">Locked</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection