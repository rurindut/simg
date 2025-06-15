<div class="space-y-2 p-4">
    <div><strong>NIA:</strong> {{ $record->nia }}</div>
    <div><strong>Nama:</strong> {{ $record->nama }}</div>
    <div><strong>Jenis Kelamin:</strong> {{ $record->jenis_kelamin }}</div>
    <div><strong>Tanggal Lahir:</strong> {{ $record->tanggal_lahir?->format('d-m-Y') }}</div>
    <div><strong>Alamat:</strong> {{ $record->alamat }}</div>
    <div><strong>Email:</strong> {{ $record->email }}</div>
    <div><strong>No HP:</strong> {{ $record->nomor_hp }}</div>
    <div><strong>Wilayah:</strong> {{ $record->region?->name ?? 'Menunggu' }}</div>
    <div><strong>Kelompok:</strong> {{ $record->cluster?->name ?? 'None' }}</div>
    <div><strong>Alamat:</strong> {{ $record->alamat_ktp }}</div>
    <div><strong>Suku:</strong> {{ $record->suku?->name ?? 'Kosong' }}</div>
    <div><strong>Hobi:</strong> {{ $record->hobi?->name ?? 'Kosong' }}</div>
    <div><strong>Minat:</strong> {{ $record->minat?->name ?? 'Kosong' }}</div>
</div>