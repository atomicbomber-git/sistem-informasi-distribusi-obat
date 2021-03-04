<nav class="nav col-md-2 flex-column">
    <x-menu-title>
        @lang("application.menu")
    </x-menu-title>

    <x-menu-item :href="route('produk.index')" routeIs="produk.*" >
        <i class="bi-box"></i>
        @lang("application.product")
    </x-menu-item>

    <x-menu-item :href="route('faktur-penjualan.index')" routeIs='faktur-penjualan.*' >
        <i class="bi-card-list"></i>
        @lang("application.sales_invoice")
    </x-menu-item>
</nav>