<!-- Ganti seluruh bagian Sifat Dana dengan kode ini -->
<div class="mb-4 bg-gray-50 p-4 rounded border border-gray-200">
    <label class="block text-gray-800 text-sm font-bold mb-3">Sifat Dana</label>
    <div class="space-y-2">
        <!-- Opsi Umum -->
        <label class="flex items-start cursor-pointer group">
            <input type="radio" name="is_public" value="1" id="tipe_umum" checked onchange="toggleUserSelect()">
            <div class="ml-2">
                <span class="font-semibold text-gray-800 group-hover:text-blue-600">Umum / Sedekah / Dana Sosial</span>
                <p class="text-xs text-gray-500">Contoh: Kotak Amal, Sedekah Jumat. (Akan menambah saldo total ruangan saja).</p>
            </div>
        </label>

        <!-- Opsi Private (Tempat Kas) -->
        <label class="flex items-start cursor-pointer group">
            <input type="radio" name="is_public" value="0" id="tipe_private" onchange="toggleUserSelect()">
            <div class="ml-2">
                <span class="font-semibold text-gray-800 group-hover:text-blue-600">Private / Tempat Kas (Perorangan)</span>
                <p class="text-xs text-gray-500">Contoh: Kas Wajib Kelas, Tabungan Pribadi. (Akan menambah saldo pribadi tiap anggota).</p>
            </div>
        </label>
    </div>
</div>

<!-- Dropdown User (Muncul Hanya jika Private) -->
<div id="user-select-box" class="mb-4 transition-all duration-300">
    <label class="block text-gray-700 text-sm font-bold mb-2">Siapa pengurus tempat ini?</label>
    <select name="user_id" id="user_dropdown" class="w-full border border-gray-300 rounded-md p-2 bg-white">
        <option value="">-- Pilih Anggota (Opsional jika Private)</option>
        @foreach($anggota as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
        @endforeach
    </select>
    <p class="text-xs text-gray-500 mt-1">Wajib diisi jika memilih tipe "Private".</p>
</div>

<!-- Script Javascript (Letak di bawah form -->
<script>
    function toggleUserSelect() {
        const isPrivate = document.getElementById('tipe_private').checked;
        const userBox = document.getElementById('user-select-box');
        const userSelect = document.getElementById('user_dropdown');

        if (isPrivate) {
            // Tampilkan dan Wajib isi
            userBox.classList.remove('hidden', 'opacity-50');
            userBox.classList.add('block', 'opacity-100');
            userSelect.setAttribute('required', 'required');
        } else {
            // Sembunyikan dan Tidak Wajib isi
            userBox.classList.add('hidden', 'opacity-50');
            userBox.classList.remove('block', 'opacity-100');
            userSelect.removeAttribute('required');
            userSelect.value = ""; // Reset pilihan
        }
    }

    // Jalankan saat halaman load pertama kali
    document.addEventListener('DOMContentLoaded', toggleUserSelect);
</script>