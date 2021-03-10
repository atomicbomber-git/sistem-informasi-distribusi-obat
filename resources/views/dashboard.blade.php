<x-layouts.app>
    <x-feature-title>
        @lang("application.dashboard")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.dashboard")
        </li>
    </x-breadcrumb>

    <div class="row">
        <div class="col">
            <a href="{{ route("produk.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                <x-icon-product/>
                @lang("application.product")
            </a>
        </div>
        <div class="col">
            <a href="{{ route("faktur-pembelian.index") }}" class="w-100 text-start btn btn-lg btn-dark">
                <x-icon-purchase-invoice/>
                @lang("application.purchase_invoice")
            </a>
        </div>
    </div>
</x-layouts.app>