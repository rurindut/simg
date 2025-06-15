<div class="space-y-6">

    {{-- PENGALAMAN GEREJAWI --}}
    <div>
        <h3 class="text-lg font-bold text-gray-800">Pengalaman Gerejawi</h3>

        @if ($record->pengalamanGerejawis->isNotEmpty())
            <div class="grid md:grid-cols-2 gap-4 mt-2">
                @foreach ($record->pengalamanGerejawis as $item)
                    <div class="border p-3 rounded-md bg-gray-50">
                        <div><strong>Jenis:</strong> {{ $item->jenis }}</div>
                        <div><strong>Nama:</strong> {{ $item->nama }}</div>
                        <div><strong>Tahun:</strong> {{ $item->tahun }}</div>
                        <div><strong>Keterangan:</strong> {{ $item->keterangan }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 italic mt-2">Belum ada pengalaman gerejawi.</div>
        @endif
    </div>

    {{-- AKTIVITAS SOSIAL --}}
    <div>
        <h3 class="text-lg font-bold text-gray-800">Aktivitas Sosial</h3>

        @if ($record->aktivitasSosials->isNotEmpty())
            <div class="grid md:grid-cols-2 gap-4 mt-2">
                @foreach ($record->aktivitasSosials as $item)
                    <div class="border p-3 rounded-md bg-gray-50">
                        <div><strong>Jenis:</strong> {{ $item->jenis }}</div>
                        <div><strong>Nama:</strong> {{ $item->nama }}</div>
                        <div><strong>Tahun:</strong> {{ $item->tahun }}</div>
                        <div><strong>Keterangan:</strong> {{ $item->keterangan }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 italic mt-2">Belum ada pengalaman sosial.</div>
        @endif
    </div>

    {{-- PENGALAMAN PEKERJAAN --}}
    <div>
        <h3 class="text-lg font-bold text-gray-800">Riwayat Pekerjaan</h3>

        @if ($record->pekerjaans->isNotEmpty())
            <div class="grid md:grid-cols-2 gap-4 mt-2">
                @foreach ($record->pekerjaans as $item)
                    <div class="border p-3 rounded-md bg-gray-50">
                        <div><strong>Nama:</strong> {{ $item->nama }}</div>
                        <div><strong>Jabatan:</strong> {{ $item->jabatan }}</div>
                        <div><strong>Tahun:</strong> {{ $item->tahun }}</div>
                        <div><strong>Keterangan:</strong> {{ $item->keterangan }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 italic mt-2">Belum ada riwayat pekerjaan.</div>
        @endif
    </div>

</div>
