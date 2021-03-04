@foreach(session("messages") ?? [] as $message)

<div class="my-3 alert alert-{{ $message['state'] ?? \App\Enums\MessageState::STATE_INFO }}">
    @switch($message['state'] ?? 'primary')
        @case(\App\Enums\MessageState::STATE_INFO)
        <i class="bi-info-circle"></i>
        @break
        @case(\App\Enums\MessageState::STATE_SUCCESS)
        <i class="bi-check-circle"></i>
        @break
        @case(\App\Enums\MessageState::STATE_WARNING)
        <i class="bi-exclamation-circle"></i>
        @break
        @case(\App\Enums\MessageState::STATE_DANGER)
        <i class="bi-x-circle"></i>
        @break
    @endswitch
    {{ $message['content'] ?? 'Default message content.' }}
</div>

@endforeach
