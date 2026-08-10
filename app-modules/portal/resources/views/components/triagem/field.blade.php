@props([
    'label',
    'for' => null,
    'required' => false,
    'optional' => false,
    'hint' => null,
    'error' => null,
])

<div {{ $attributes->class('flex flex-col gap-2.5') }}>
    <div class="flex flex-wrap items-baseline justify-between gap-2">
        <label @if ($for) for="{{ $for }}" @endif class="text-text-high text-sm font-semibold">
            {{ $label }}
            @if ($required)
                <span class="text-helper-error ml-1">*</span>
            @endif
            @if ($optional)
                <span class="text-text-medium ml-2 font-mono text-[11px] font-normal">opcional</span>
            @endif
        </label>
    </div>

    @if ($hint)
        <p class="text-text-medium -mt-1 text-[13px]">{{ $hint }}</p>
    @endif

    {{ $slot }}

    @if ($error)
        <p role="alert" class="text-helper-error flex items-center gap-1.5 font-mono text-xs">
            <x-heroicon-o-exclamation-circle class="h-3.5 w-3.5 shrink-0" />
            {{ $error }}
        </p>
    @endif
</div>
