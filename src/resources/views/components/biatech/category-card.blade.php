@props(['category'])
<a class="category-tile tone-{{ $category['tone'] }}" href="{{ route('biatech.catalog', ['category' => $category['slug']]) }}">
    <span class="category-tile__graphic">
        <span class="category-tile__disc"><x-biatech.icon :name="$category['icon']" :size="49" /></span>
        <span class="category-tile__glow"></span>
    </span>
    <strong>{{ $category['name'] }}</strong>
    <span class="category-tile__desc">{{ $category['description'] }}</span>
    <span class="category-tile__link">Lihat produk <x-biatech.icon name="arrow-right" :size="14" /></span>
</a>
