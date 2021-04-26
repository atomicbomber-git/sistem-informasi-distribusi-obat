<nav class="nav col-md-2 flex-column">
    <x-menu-title>
        @lang("application.menu")
    </x-menu-title>

    <x-menu-subtitle>
        @lang("application.master-data")
    </x-menu-subtitle>

    <x-menu-item :href="route('produk.index')" routeIs="produk.*" >
        <x-icon-product/>
        @lang("application.product")
    </x-menu-item>

    <x-menu-item :href="route('pelanggan.index')" routeIs='pelanggan.*' >
        <x-icon-customer/>
        @lang("application.customer")
    </x-menu-item>

{{--    <x-menu-item :href="route('dashboard')" routeIs="dashboard" >--}}
{{--        <x-icon-dashboard/>--}}
{{--        @lang("application.dashboard")--}}
{{--    </x-menu-item>--}}

{{--    <x-menu-item :href="route('admin.user.edit')" routeIs='admin.user.*' >--}}
{{--        <x-icon-user/>--}}
{{--        @lang("application.admin_user")--}}
{{--    </x-menu-item>--}}

    <x-menu-subtitle>
        @lang("application.invoice")
    </x-menu-subtitle>

    <x-menu-item :href="route('faktur-pembelian.index')" routeIs='faktur-pembelian.*' >
        <x-icon-purchase-invoice/>
        @lang("application.purchase_invoice")
    </x-menu-item>

    <x-menu-item :href="route('faktur-penjualan.index')" routeIs='faktur-penjualan.*' >
        <x-icon-sales-invoice/>
        @lang("application.sales_invoice")
    </x-menu-item>
</nav>