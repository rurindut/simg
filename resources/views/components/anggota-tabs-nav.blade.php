@props(['anggota'])

@php
    $base = "/admin/anggota/{$anggota->id}/edit";
@endphp

<div class="fi-tabs flex max-w-full gap-x-1 overflow-x-auto mx-auto rounded-xl bg-white p-2 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
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
            $current = '/' . ltrim(request()->path(), '/');
            $target = '/' . ltrim(parse_url($url, PHP_URL_PATH), '/');
            $isActive = $current === $target;
        @endphp
        <a
            href="{{ $url }}"
            @class([
                'fi-tabs-item group flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75',
                'hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5' => ! $isActive,
                'fi-active fi-tabs-item-active bg-gray-50 dark:bg-white/5' => $isActive,
            ])
        >
            <span
                @class([
                    'fi-tabs-item-label transition duration-75',
                    'text-primary-600 dark:text-primary-400' => $isActive,
                    'text-gray-500 group-hover:text-gray-700 group-focus-visible:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200 dark:group-focus-visible:text-gray-200' => ! $isActive,
                ])
            >
                {{ $label }}
            </span>
        </a>
    @endforeach
    </div>
</div>
