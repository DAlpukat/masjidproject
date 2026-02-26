@extends('layouts.app')

@section('content')
<!-- TAMBAHKAN BACKGROUND DI SINI AGAR TEMA MASUK -->
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header Section -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-pink-400 uppercase bg-pink-500/10 border border-pink-500/20 rounded-full inline-block mb-3">
                        Manajemen Anggota
                    </span>
                    <h1 class="text-3xl font-black text-white tracking-tight">
                        Daftar <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">Anggota</span>
                    </h1>
                    <p class="text-gray-400 font-medium mt-1">{{ $tempat->nama }}</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-pink-400 hover:text-pink-300 transition-colors">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="glass-card p-6 rounded-2xl border border-white/10 shadow-lg">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-pink-500/10 rounded-xl border border-pink-500/20">
                        <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Anggota</p>
                        <p class="text-2xl font-black text-white">{{ $members->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="glass-card p-6 rounded-2xl border border-white/10 shadow-lg">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Status</p>
                        <p class="text-lg font-bold {{ $tempat->status == 'aktif' ? 'text-emerald-400' : 'text-yellow-400' }}">
                            {{ $tempat->status == 'aktif' ? 'Aktif' : 'Pending' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="glass-card p-6 rounded-2xl border border-white/10 shadow-lg">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-500/10 rounded-xl border border-blue-500/20">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path fill="currentColor" d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.523 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Akses</p>
                        <p class="text-lg font-bold {{ $tempat->is_public ? 'text-blue-400' : 'text-purple-400' }}">
                            {{ $tempat->is_public ? 'Publik' : 'Privat' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="glass-card rounded-[2rem] border border-white/10 shadow-xl overflow-hidden">
            @if($members->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/10">
                                <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Nama Anggota</th>
                                <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Tanggal Bergabung</th>
                                <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($members as $member)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center">
                                        <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-pink-500/20 to-purple-500/20 border border-pink-500/20 flex items-center justify-center text-pink-400 font-bold shadow-inner">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-white">{{ $member->name }}</div>
                                            <div class="text-xs text-gray-500">ID: #{{ $member->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm text-gray-400">{{ $member->email }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm text-gray-400">{{ $member->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <form id="kick-form-{{ $member->id }}" action="{{ route('admin.members.kick', ['id' => $tempat->id, 'userId' => $member->id]) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button type="button" 
                                            onclick="confirmKick('{{ $member->id }}', '{{ $member->name }}')"
                                            class="group/btn relative inline-flex items-center justify-center p-2.5 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-all border border-transparent hover:border-red-500/20"
                                            title="Keluarkan Anggota">
                                        <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="py-20 text-center">
                    <div class="w-20 h-20 bg-pink-500/10 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-pink-500/20">
                        <svg class="w-10 h-10 text-pink-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Anggota</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Belum ada anggota biasa yang bergabung ke room ini. Bagikan kode referral atau undang pengguna untuk bergabung.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .swal2-popup {
        border-radius: 1.5rem !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background: rgba(15, 15, 15, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    
    .swal2-title {
        color: #ffffff !important;
    }
    
    .swal2-html-container {
        color: #9ca3af !important;
    }
    
    .swal2-confirm {
        border-radius: 0.75rem !important;
    }
    
    .swal2-cancel {
        border-radius: 0.75rem !important;
    }
</style>

<script>
    // 1. Logika Pop-up Konfirmasi
    function confirmKick(memberId, memberName) {
        Swal.fire({
            title: 'Keluarkan Anggota?',
            text: "Akses " + memberName + " ke komunitas ini akan dicabut.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ec4899', 
            cancelButtonColor: '#374151',  
            confirmButtonText: 'Ya, Keluarkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: 'rgba(15, 15, 15, 0.95)',
            color: '#ffffff',
            border: '1px solid rgba(255, 255, 255, 0.1)'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('kick-form-' + memberId).submit();
            }
        })
    }

    // 2. Logika Notifikasi Sukses
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            background: 'rgba(15, 15, 15, 0.95)',
            color: '#ffffff',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}"
        });
    @endif

    // 3. Logika Notifikasi Error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            confirmButtonColor: '#ec4899',
            background: 'rgba(15, 15, 15, 0.95)',
            color: '#ffffff'
        });
    @endif
</script>
@endsection
