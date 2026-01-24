@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('laporan.show', [$tempat->slug, $pageId]) }}" class="text-blue-600 hover:underline">&larr; Kembali ke Laporan</a>
            <h1 class="text-3xl font-bold mt-2">Tambah Laporan Kas</h1>
            <p class="text-gray-600">Ruangan: {{ $tempat->nama }}</p>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('laporan.store') }}">
                @csrf
                
                <!-- Hidden ID Tempat -->
                <input type="hidden" name="tempat_layanan_id" value="{{ $tempat->id }}">

                <!-- Hidden ID Page (INI YANG PENTING & KURANG TADI) -->
                <input type="hidden" name="page_id" value="{{ $pageId }}">

                <!-- 1. Tanggal -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-md p-2" required value="{{ date('Y-m-d') }}">
                </div>

                <!-- 2. Jenis Transaksi (Masuk/Keluar) -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jenis</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="jenis" value="masuk" checked class="text-blue-600">
                            <span class="ml-2">Pemasukan (Masuk)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="jenis" value="keluar" class="text-red-600">
                            <span class="ml-2">Pengeluaran (Keluar)</span>
                        </label>
                    </div>
                </div>

                <!-- 3. Sifat Transaksi (Personal vs Umum) -->
                <div class="mb-4 bg-gray-50 p-4 rounded border border-gray-200">
                    <label class="block text-gray-800 text-sm font-bold mb-3">Sifat Dana / Uang</label>
                    <div class="space-y-2">
                        <!-- Opsi Personal -->
                        <label class="flex items-start cursor-pointer group">
                            <input type="radio" name="sifat_transaksi" value="personal" id="sifat_personal" class="mt-1" checked onchange="toggleUserSelect()">
                            <div class="ml-2">
                                <span class="font-semibold text-gray-800 group-hover:text-blue-600">Personal / Perorangan</span>
                                <p class="text-xs text-gray-500">Contoh: Kas Wajib Kelas, Tabungan Pribadi. (Akan menambah saldo user tertentu).</p>
                            </div>
                        </label>

                        <!-- Opsi Umum -->
                        <label class="flex items-start cursor-pointer group">
                            <input type="radio" name="sifat_transaksi" value="umum" id="sifat_umum" class="mt-1" onchange="toggleUserSelect()">
                            <div class="ml-2">
                                <span class="font-semibold text-gray-800 group-hover:text-blue-600">Umum / Sedekah / Dana Sosial</span>
                                <p class="text-xs text-gray-500">Contoh: Kotak Amal, Sedekah Jumat. (Akan menambah saldo total ruangan saja).</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 4. Dropdown Pilih User (Muncul Hanya jika Personal) -->
                <div id="user-select-box" class="mb-4 transition-all duration-300">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Siapa yang menyetor/menanggung?</label>
                    <select name="user_id" id="user_dropdown" class="w-full border border-gray-300 rounded-md p-2 bg-white">
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($anggota as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Wajib diisi jika memilih tipe "Personal".</p>
                </div>

                <!-- 5. Jumlah -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Uang (Rp)</label>
                    <input type="number" name="jumlah" class="w-full border border-gray-300 rounded-md p-2" required min="1" placeholder="Contoh: 100000">
                </div>

                <!-- 6. Keterangan -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan / Alasan</label>
                    <textarea name="keterangan" class="w-full border border-gray-300 rounded-md p-2" rows="3" required placeholder="Contoh: Iuran kas mingguan..."></textarea>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end">
                    <a href="{{ route('laporan.show', [$tempat->slug, $pageId]) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md mr-2 hover:bg-gray-400">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-bold">
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk Logic Show/Hide User -->
<script>
    function toggleUserSelect() {
        const isPersonal = document.getElementById('sifat_personal').checked;
        const userBox = document.getElementById('user-select-box');
        const userSelect = document.getElementById('user_dropdown');

        if (isPersonal) {
            // Tampilkan dan Wajib isi
            userBox.classList.remove('hidden', 'opacity-50');
            userBox.classList.add('block', 'opacity-100');
            userSelect.setAttribute('required', 'required');
        } else {
            // Sembunyikan dan Tidak Wajib
            userBox.classList.add('hidden', 'opacity-50');
            userBox.classList.remove('block', 'opacity-100');
            userSelect.removeAttribute('required');
            userSelect.value = ""; // Reset pilihan
        }
    }

    // Jalankan saat halaman load pertama kali
    document.addEventListener('DOMContentLoaded', toggleUserSelect);
</script>
@endsection