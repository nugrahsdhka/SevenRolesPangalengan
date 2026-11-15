@props(['status'])

@php
    $map = [
        'baru'     => 'bg-blue-50 text-blue-700 ring-blue-200',
        'diproses' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'selesai'  => 'bg-green-50 text-green-700 ring-green-200',
        'ditolak'  => 'bg-gray-100 text-gray-600 ring-gray-200',
    ];
    $class = $map[$status] ?? 'bg-gray-100 text-gray-600 ring-gray-200';
    $label = \App\Models\Complaint::STATUS[$status] ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] sm:text-xs font-semibold ring-1 ring-inset $class"]) }}>
    {{ $label }}
</span>
