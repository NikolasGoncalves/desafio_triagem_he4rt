@props([
    'field',
    'options' => [],
    'selected' => [],
])

{{--
    Seleção múltipla em formato de "chips". Não existe equivalente direto em
    x-he4rt::* nem em Flux (core), então usamos botões nativos dirigidos pelo
    Livewire (método `toggle`), preservando a microinteração do protótipo.
--}}
<div {{ $attributes->class('flex flex-wrap gap-2') }} role="group">
    @foreach ($options as $option)
        @php $active = in_array($option, $selected, true); @endphp

        <button
            type="button"
            wire:click="toggle('{{ $field }}', @js($option))"
            wire:loading.attr="disabled"
            aria-pressed="{{ $active ? 'true' : 'false' }}"
            @class([
                'rounded-full border px-3.5 py-2 text-[13px] transition-all',
                'focus-visible:ring-primary focus-visible:outline-none focus-visible:ring-2',
                'from-primary to-secondary text-text-light border-transparent bg-gradient-to-br font-medium' => $active,
                'border-outline-medium text-text-medium hover:border-primary hover:text-text-high bg-transparent' => !$active,
            ])
        >
            {{ $option }}
        </button>
    @endforeach
</div>
