@props(['name', 'size' => 20])
<svg {{ $attributes->class(['ui-icon']) }} width="{{ $size }}" height="{{ $size }}" aria-hidden="true" focusable="false">
    <use href="{{ asset('biatech/icons.svg') }}#{{ $name }}"></use>
</svg>
