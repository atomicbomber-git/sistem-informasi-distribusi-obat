<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ \App\Providers\RouteServiceProvider::home()  }}">
                {{ config("app.name")  }}
            </a>
        </li>
        {{ $slot }}
    </ol>
</nav>