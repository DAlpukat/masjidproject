<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Keterangan
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Sifat
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Jenis
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Jumlah
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Bukti
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Aksi
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($laporans as $item)
            <tr>
                <!-- Tanggal (Pakai Accessor) -->
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $item->tanggal_format }}
                </td>
                
                <!-- Keterangan -->
                <td class="px-6 py-4">
                    {{ $item->keterangan }}
                </td>

                <!-- Sifat Transaksi (Personal vs Umum) -->
                <td class="px-6 py-4">
                    @if($item->sifat_transaksi == 'personal')
                        <span class="text-blue-600 text-xs">({{ $item->user->name }})</span>
                    @else
                        <span class="text-gray-400 text-xs">Umum</span>
                    @endif
                </td>

                <!-- Jenis (Masuk / Keluar) -->
                <td class="px-6 py-4">
                    @if($item->jenis == 'masuk')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Masuk
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Keluar
                        </span>
                    @endif
                </td>

                <!-- Jumlah (Pakai Accessor) -->
                <td class="px-6 py-4 font-bold">
                    {{ $item->jumlah_rupiah }}
                </td>

                <!-- Bukti -->
                <td class="px-6 py-4">
                    @if($item->bukti_foto)
                        <a href="#" class="text-blue-500 hover:underline">Lihat</a>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>

                <!-- Aksi (Edit/Hapus) -->
                <td class="px-6 py-4 text-sm font-medium">
                    @if(auth()->id() == $item->tempatLayanan->user_id)
                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                        <!-- Form Hapus -->
                        <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    @else
                        <span class="text-gray-400 italic text-xs">Read Only</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                    Belum ada laporan.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>