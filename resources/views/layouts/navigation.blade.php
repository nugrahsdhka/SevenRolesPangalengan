<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    {{ __('Dashboard') }}
</x-nav-link>

@role('Divisi Gizi')
<x-nav-link :href="route('gizi.index')" :active="request()->routeIs('gizi.*')">
    {{ __('Data Gizi') }}
</x-nav-link>
@endrole

@role('Divisi Pencegahan')
<x-nav-link :href="route('pencegahan.index')" :active="request()->routeIs('pencegahan.*')">
    {{ __('Data Pencegahan') }}
</x-nav-link>
@endrole

@role('Divisi Penanggulangan')
<x-nav-link :href="route('penanggulangan.index')" :active="request()->routeIs('penanggulangan.*')">
    {{ __('Data Penanggulangan') }}
</x-nav-link>
@endrole

@role('Divisi Desa')
<x-nav-link :href="route('desa.index')" :active="request()->routeIs('desa.*')">
    {{ __('Data Desa') }}
</x-nav-link>
@endrole

@role('Camat')
<x-nav-link :href="route('camat.review')" :active="request()->routeIs('camat.*')">
    {{ __('Review Data') }}
</x-nav-link>
@endrole

@role('IT Admin')
<x-nav-link :href="route('admin.published.index')" :active="request()->routeIs('admin.published.index')">
    {{ __('Laporan Publish') }}
</x-nav-link>
@endrole
