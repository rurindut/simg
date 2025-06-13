@props(['anggota'])

@php
    $base = "/admin/anggota/{$anggota->id}/edit";
    $current = request()->path();
@endphp

<!-- <div class="inline-flex rounded-full border border-gray-200 bg-white p-1 text-sm shadow-sm"> -->
    @php
        $tabs = [
            'Data Pribadi' => route('filament.admin.resources.anggota.edit', $anggota),
            'Data Baptis' => "{$base}/data-baptis",
            'Atestasi' => "{$base}/data-atestasi",
            'Data Keluarga' => "{$base}/data-keluarga",
            'Pengalaman-Aktivitas-Pekerjaan' => "{$base}/data-aktivitas",
        ];
    @endphp

    <div class="flex space-x-2">
    @foreach ($tabs as $label => $url)

        @php
            $isActive = str($current)->startsWith($url);
        @endphp
        <a
            href="{{ $url }}"
            class="px-4 py-2 rounded text-white font-semibold transition
                {{ $isActive ? 'bg-amber-500 text-white' : 'bg-gray-500 text-white hover:bg-gray-600' }}">
            {{ $label }}
        </a>
    @endforeach
    </div>
<!-- </div> -->
