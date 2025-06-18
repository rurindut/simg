
<div class="pt-2">
    <div class="font-medium text-muted">NIA</div>
    <div class="text-foreground">{{ $record->nia ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">Email</div>
    <div class="text-foreground">{{ $record->email ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">No Hp</div>
    <div class="text-foreground">{{ $record->nomor_hp ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">Wilayah</div>
    <div class="text-foreground">{{ $record->wilayah?->nama ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">Kelompok</div>
    <div class="text-foreground">{{ $record->kelompok?->nama ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">Alamat</div>
    <div class="text-foreground">{{ $record->alamat ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">Suku</div>
    <div class="text-foreground">{{ $record->suku?->nama ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">Status Tinggal</div>
    <div class="text-foreground">{{ ucfirst($record->status_tinggal) ?? '-' }}</div>
</div>
<div class="pt-2">
    <div class="font-medium text-muted">Status Hidup</div>
    <div class="text-foreground">{{ ucfirst($record->status_hidup) ?? '-' }}</div>
</div>
