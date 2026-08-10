{{--
    Aviso de apoio (equivalente ao InfoCallout do protótipo). Usa os tokens do
    Design System com borda tracejada para diferenciar de um card padrão.
--}}
<div
    {{ $attributes->class('border-primary bg-primary/10 text-text-high flex gap-3 rounded-md border border-dashed p-3.5 text-[13px]') }}
>
    <x-heroicon-o-check class="text-primary mt-0.5 h-4 w-4 shrink-0" />
    <div class="leading-relaxed">
        {{ $slot }}
    </div>
</div>
