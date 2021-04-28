<x-layouts.app>
    <x-feature-title>
        <x-icon-dashboard/>
        @lang("application.dashboard")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page">
            @lang("application.dashboard")
        </li>
    </x-breadcrumb>

    <div class="card">
        <div class="row">
            <div class="card col-md-4 p-3">
                <h2 class="h5 mt-2"> @lang("application.master-data") </h2>

                <x-dashboard-item>
                    <a href="{{ route("produk.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                        <x-icon-product/>
                        @lang("application.product")
                    </a>
                </x-dashboard-item>

                <x-dashboard-item>
                    <a href="{{ route("pemasok.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                        <x-icon-customer/>
                        @lang("application.supplier")
                    </a>
                </x-dashboard-item>

                <x-dashboard-item>
                    <a href="{{ route("pelanggan.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                        <x-icon-customer/>
                        @lang("application.customer")
                    </a>
                </x-dashboard-item>
            </div>

            <div class="card col-md-4 p-3">
                <h2 class="h5 mt-2"> @lang("application.invoice") </h2>

                <x-dashboard-item>
                    <a href="{{ route("faktur-pembelian.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                        <x-icon-purchase-invoice/>
                        @lang("application.purchase_invoice")
                    </a>
                </x-dashboard-item>

                <x-dashboard-item>
                    <a href="{{ route("faktur-penjualan.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                        <x-icon-sales-invoice/>
                        @lang("application.sales_invoice")
                    </a>
                </x-dashboard-item>
            </div>

            <div class="card col-md-4 p-3">
                <h2 class="h5 mt-2"> @lang("application.return") </h2>

                <x-dashboard-item>
                    <a href="{{ route("retur-penjualan.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                        <x-icon-sales-return/>
                        @lang("application.sales-return")
                    </a>
                </x-dashboard-item>
            </div>
        </div>
    </div>
</x-layouts.app>