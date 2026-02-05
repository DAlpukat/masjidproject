@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="min-h-screen py-10 px-4" style="background: radial-gradient(at 0% 0%, rgba(255, 192, 203, 0.4) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(221, 160, 221, 0.3) 0px, transparent 50%), #ffffff;">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Anggota</h1>
                <p class="text-sm text-gray-500">{{ $tempat->nama }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 font-bold flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="glass-card p-4 rounded-2xl border border-white shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-pink-100 rounded-xl text-pink-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Anggota</p>
                    <p class="text-xl font-black text-gray-800">{{ $members->count() }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-[2rem] border border-white shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/50">
                            <th class="px-6 py-4 text-sm font-black text-gray-700 uppercase tracking-wider">Nama Anggota</th>
                            <th class="px-6 py-4 text-sm font-black text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-sm font-black text-gray-700 uppercase tracking-wider">Tanggal Bergabung</th>
                            <th class="px-6 py-4 text-sm font-black text-gray-700 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($members as $member)
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-pink-400 to-purple-500 flex items-center justify-center text-white font-bold shadow-sm">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $member->name }}</div>
                                        @if($member->id == $tempat->user_id)
                                            <span class="text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-extrabold uppercase">Owner</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $member->email }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $member->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($member->id != $tempat->user_id)
                                    <form id="kick-form-{{ $member->id }}" action="{{ route('admin.members.kick', ['id' => $tempat->id, 'userId' => $member->id]) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button type="button" 
                                            onclick="confirmKick('{{ $member->id }}', '{{ $member->name }}')"
                                            class="group relative inline-flex items-center justify-center p-2 text-red-500 hover:bg-red-50 rounded-xl transition-all shadow-sm hover:shadow-red-200 border border-transparent hover:border-red-100"
                                            title="Kick User">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-xs italic text-gray-400">No Action</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($members->isEmpty())
                <div class="py-20 text-center">
                    <p class="text-gray-500 font-medium">Belum ada anggota yang bergabung.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .swal2-popup {
        border-radius: 2rem !important;
        font-family: inherit;
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
            cancelButtonColor: '#6b7280',  
            confirmButtonText: 'Ya, Keluarkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: `rgba(236, 72, 153, 0.1)` // Backdrop pink tipis
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('kick-form-' + memberId).submit();
            }
        })
    }

    // 2. Logika Notifikasi Sukses (Dijalankan setelah halaman refresh)
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            background: '#ffffff',
            color: '#1f2937'
        });
    @endif

    // 3. Logika Notifikasi Error (Jika ada)
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            confirmButtonColor: '#ec4899'
        });
    @endif
</script>
@endsection