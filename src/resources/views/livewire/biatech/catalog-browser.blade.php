<div class="catalog-layout">
    <aside class="filter-panel" aria-label="Filter katalog">
        <h3><x-biatech.icon name="filter" :size="19" /> Filter Produk</h3>
        <div class="filter-group">
            <label for="catalog-search">Cari produk</label>
            <input id="catalog-search" class="field-control" type="search" wire:model.live.debounce.350ms="query" placeholder="Nama produk atau provider...">
        </div>
        <div class="filter-group">
            <label for="catalog-category">Kategori</label>
            <select id="catalog-category" class="field-control" wire:model.live="category">
                <option value="">Semua kategori</option>
                @foreach($categories as $item)
                    <option value="{{ $item['slug'] }}">{{ $item['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label for="catalog-provider">Provider</label>
            <select id="catalog-provider" class="field-control" wire:model.live="provider">
                <option value="">Semua provider</option>
                @foreach($providers as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" class="button button--outline button--full" wire:click="clearFilters">Bersihkan filter</button>
    </aside>
    <div>
        <div class="catalog-toolbar">
            <p>Menampilkan <strong>{{ count($products) }}</strong> produk</p>
            <div class="catalog-sort">
                <label for="catalog-sort">Urutkan:</label>
                <select id="catalog-sort" class="field-control" wire:model.live="sort">
                    <option value="recommended">Rekomendasi</option>
                    <option value="price_low">Harga terendah</option>
                    <option value="price_high">Harga tertinggi</option>
                    <option value="name">Nama A—Z</option>
                </select>
            </div>
        </div>
        <div wire:loading.delay class="notice" role="status" style="margin-bottom:14px"><x-biatech.icon name="refresh" :size="17" /> Memperbarui daftar produk...</div>
        @if(count($products))
            <div class="catalog-products">
                @foreach($products as $product)
                    <div wire:key="product-{{ $product['slug'] }}"><x-biatech.product-card :product="$product" /></div>
                @endforeach
            </div>
        @else
            <div class="empty-state"><x-biatech.icon name="search" :size="45" /><h3>Belum ada produk yang cocok</h3><p>Silakan ubah filter atau kata kunci pencarian.</p><button type="button" class="button button--orange" wire:click="clearFilters">Tampilkan semua produk</button></div>
        @endif
    </div>
</div>
