@props([
    'steps' => [],
    'current' => 0,
    'progress' => 0,
])

<div {{ $attributes->class('flex flex-col gap-3') }}>
    <ol class="flex items-center gap-2" aria-label="Etapas">
        @foreach ($steps as $index => $label)
            @php
                $done = $index < $current;
                $active = $index === $current;
            @endphp

            <li class="flex flex-1 items-center gap-2">
                <span
                    @class([
                        'flex h-6 w-6 shrink-0 items-center justify-center rounded-full font-mono text-[11px] font-bold transition-colors',
                        'from-primary to-secondary text-text-light bg-gradient-to-br' => $active,
                        'bg-primary/20 text-primary' => $done,
                        'bg-elevation-03dp text-text-medium' => !$active && !$done,
                    ])
                    @if ($active) aria-current="step" @endif
                >
                    @if ($done)
                        <x-heroicon-o-check class="h-3.5 w-3.5" />
                    @else
                        {{ $index + 1 }}
                    @endif
                </span>

                <span
                    @class([
                        'hidden truncate text-sm sm:block',
                        'text-text-high font-medium' => $active,
                        'text-text-medium' => !$active,
                    ])
                >
                    {{ $label }}
                </span>
            </li>
        @endforeach
    </ol>

    <div class="bg-elevation-03dp h-1.5 overflow-hidden rounded-full">
        <div
            class="from-primary to-secondary h-full rounded-full bg-gradient-to-br transition-[width] duration-300"
            style="width: {{ $progress }}%"
        ></div>
    </div>
</div>
