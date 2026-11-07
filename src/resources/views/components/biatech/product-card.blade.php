@props(['product', 'large' => false])
<article class="product-card {{ $large ? 'product-card--large' : '' }}">
    <a class="product-card__visual tone-{{ $product['tone'] }}" href="{{ route('biatech.product', $product['slug']) }}" aria-label="Lihat {{ $product['name'] }}">
        <span class="product-card__visual-bubble"><x-biatech.icon :name="$product['icon']" :size="$large ? 70 : 54" /></span>
        <span class="product-card__visual-label">{{ $product['provider'] }}</span>
        <span class="product-card__visual-orbit"></span>
    </a>
    <div class="product-card__body">
        <span class="product-card__category">{{ $product['category_name'] }}</span>
        <a class="product-card__name" href="{{ route('biatech.product', $product['slug']) }}">{{ $product['name'] }}</a>
        <div class="product-card__meta">
            @if (! empty($product['quota']))<span><x-biatech.icon name="check" :size="13" /> {{ $product['quota'] }}</span>@endif
            @if ($product['validity_days'])<span><x-biatech.icon name="clock" :size="13" /> {{ $product['validity_days'] }} hari</span>@endif
        </div>
        <div class="product-card__bottom">
            <div><small>Harga {{ config('biatech-storefront.catalog_source') === 'demo' ? 'demo' : 'jual' }}</small><strong>Rp{{ number_format($product['price'], 0, ',', '.') }}</strong></div>
            <a class="button button--orange product-card__action" href="{{ route('biatech.product', $product['slug']) }}">Pilih <x-biatech.icon name="arrow-right" :size="16" /></a>
        </div>
    </div>
</article>
