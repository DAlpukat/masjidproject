@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10 px-4" style="background: radial-gradient(at 0% 0%, rgba(255, 192, 203, 0.4) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(221, 160, 221, 0.3) 0px, transparent 50%), #ffffff;">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
            <div class="space-y-1">
                <span class="px-3 py-1 text-xs font-bold tracking-widest text-pink-500 uppercase bg-pink-100 rounded-full">
                    Control Panel
                </span>
                <h1 class="text-4xl font-black text-gray-800 tracking-tight">
                    Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">Saya</span>
                </h1>
                <p class="text-gray-500 font-medium">Kelola operasional dan anggota layanan Anda dengan mudah.</p>
            </div>
            
            <a href="{{ route('admin.temp.create') }}" class="group relative inline-flex items-center px-8 py-3.5 overflow-hidden text-white bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl shadow-xl transition-all hover:shadow-pink-200/50 active:scale-95">
                <span class="absolute right-0 w-8 h-32 -mt-12 transition-all duration-1000 transform translate-x-12 bg-white opacity-10 rotate-12 group-hover:-translate-x-40 ease"></span>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="font-bold tracking-wide">Tambah Layanan</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-white/80 backdrop-blur-md border border-pink-200 text-pink-700 p-4 mb-8 rounded-2xl shadow-sm flex items-center animate-bounce-short">
                <div class="bg-pink-500 p-1.5 rounded-lg mr-3">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-semibold text-sm">{!! session('success') !!}</span>
            </div>
        @endif

        <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-pink-50/50 to-purple-50/50">
                            <th class="px-8 py-5 text-left text-xs font-bold text-pink-900 uppercase tracking-widest border-b border-pink-100">Layanan</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-pink-900 uppercase tracking-widest border-b border-pink-100">Status</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-pink-900 uppercase tracking-widest border-b border-pink-100">Referral</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-pink-900 uppercase tracking-widest border-b border-pink-100">Anggota</th>
                            <th class="px-8 py-5 text-center text-xs font-bold text-pink-900 uppercase tracking-widest border-b border-pink-100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-50/50">
                        @forelse($tempatLayanans as $item)
                            <tr class="hover:bg-white/50 transition-all duration-300 group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-100 to-purple-100 flex items-center justify-center mr-4 text-pink-600 font-bold group-hover:scale-110 transition-transform">
                                            {{ substr($item->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-gray-900 font-bold text-lg leading-none mb-1">{{ $item->nama }}</div>
                                            <div class="text-pink-400 text-xs font-medium">#{{ $item->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($item->status == 'aktif')
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-xs font-black bg-green-50 text-green-600 border border-green-100">
                                            <span class="w-2 h-2 mr-2 bg-green-500 rounded-full shadow-[0 0 8px_rgba(34,197,94,0.6)] animate-pulse"></span> AKTIF
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-xs font-black bg-orange-50 text-orange-600 border border-orange-100">
                                            <span class="w-2 h-2 mr-2 bg-orange-400 rounded-full"></span> PENDING
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    @if($item->is_public)
                                        <div class="flex items-center text-purple-500 font-bold text-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.523 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                                            Publik
                                        </div>
                                    @else
                                        <button onclick="navigator.clipboard.writeText('{{ $item->kode_referral }}');" class="flex items-center group/btn space-x-2 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 hover:border-pink-200 transition-all">
                                            <span class="font-mono font-bold text-pink-600">{{ $item->kode_referral }}</span>
                                            <svg class="w-4 h-4 text-gray-400 group-hover/btn:text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                        </button>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex -space-x-2">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-pink-500 text-white text-[10px] font-bold border-2 border-white ring-2 ring-pink-50">
                                            {{ $item->anggota_count }}
                                        </div>
                                        <span class="pl-4 self-center text-sm font-bold text-gray-600">Orang</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <!-- Tombol Edit (BARU) -->
                                        <a href="{{ route('admin.temp.edit', $item->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Edit Pengaturan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <!-- Tombol Pages -->
                                        <a href="{{ route('pages.index', $item->id) }}" class="p-2 bg-purple-50 text-purple-600 rounded-xl hover:bg-purple-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </a>
                                        
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.temp.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-pink-50 text-pink-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="inline-flex p-6 rounded-full bg-pink-50 mb-4">
                                        <svg class="w-12 h-12 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-bold tracking-tight">BELUM ADA LAYANAN TERDAFTAR</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <p class="mt-8 text-center text-gray-400 text-xs font-medium uppercase tracking-widest">
            &copy; {{ date('Y') }} Sistem Management Layanan &bull; Versi 2.0
        </p>
    </div>
</div>
@endsection