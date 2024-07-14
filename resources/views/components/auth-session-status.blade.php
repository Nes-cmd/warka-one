@props(['status'])

@if ($status)
    @if($status['type'] == 'error')
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-red-600']) }}>
        {{ $status['message'] }}
    </div>
    @else 
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        {{ $status['message'] }}
    </div>
    @endif
@endif
