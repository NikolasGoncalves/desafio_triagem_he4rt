@props([
    'label',
    'value',
])

{{-- Célula de resumo do perfil na tela de conclusão. --}}
<div {{ $attributes->class('bg-elevation-02dp px-5 py-4') }}>
    <p class="text-text-medium font-mono text-[11px] uppercase tracking-wider">{{ $label }}</p>
    <p class="text-text-high mt-1 text-sm font-medium capitalize">{{ $value }}</p>
</div>
