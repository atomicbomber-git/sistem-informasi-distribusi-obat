<nav class="nav col-md-2 flex-column">
    <x-menu-title>
        @lang("application.menu")
    </x-menu-title>

    <x-menu-item :href="route('dashboard')" routeIs="dashboard" >
        <i class="bi-house-door-fill"></i>
        @lang("application.dashboard")
    </x-menu-item>

    <x-menu-item :href="route('produk.index')" routeIs="produk.*" >
        <i class="bi-box"></i>
        @lang("application.product")
    </x-menu-item>

    <x-menu-item :href="route('faktur-pembelian.index')" routeIs='faktur-pembelian.*' >
        <i class="bi-card-list"></i>
        @lang("application.purchase_invoice")
    </x-menu-item>
</nav>