<section class="sheet" {{ $attributes->except("style") }} style="padding: 3mm; {{ $style ?? '' }}" >
    {{ $slot }}
</section>