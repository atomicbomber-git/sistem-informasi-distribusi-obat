<nav class="nav col-md-2 flex-column">
    <x-menu-title>
        @lang("application.menu")
    </x-menu-title>

    <x-menu-item :href="route('produk.index')" routeIs="produk.*" >
        <i class="bi-box"></i>
        @lang("application.product")
    </x-menu-item>

    <x-menu-item :href="route('faktur-pembelian.index')" routeIs='faktur-pembelian.*' >
        <i class="bi-card-list"></i>
        @lang("application.purchase_invoice")
    </x-menu-item>

    <x-menu-item :href="route('stock-produk.index')" routeIs='stock-produk.*' >
        <i class="bi-bounding-box"></i>
        @lang("application.product_stock")
    </x-menu-item>
</nav>