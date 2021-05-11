@props([
    "hasLeftSide" => true,
    "hasRightSide" => true,
])

<div style="display: flex; margin-top: 20px">
    <div style="flex: 1; text-align: center">
        @if($hasLeftSide)
            <br>
            <br>
            Tanda Terima <br>
            <br>
            <br>
            <br>
            <pre> (                ) </pre>
            Nama Jelas
        @endif
    </div>
    <div style="flex: 1">
        {{ $slot }}
    </div>
    <div style="flex: 1; text-align: center; padding: 0 1rem 0 1rem">
        <br>
        <br>
        Hormat Kami <br>
        <br>
        <br>
        <br>
        <br>
        <div style="border-top: thin solid black; text-transform: uppercase">
            @if($hasRightSide)
                Admin PT. Kuburaya Mediafarma
            @endif
        </div>
    </div>
</div>
