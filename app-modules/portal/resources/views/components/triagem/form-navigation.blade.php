@props([
    'current' => 0,
    'isLast' => false,
])

<div {{ $attributes->class('border-outline-low flex items-center justify-between gap-3 border-t px-5 py-4 sm:px-6') }}>
    @if ($current > 0)
        <x-he4rt::button
            variant="outline"
            size="sm"
            rounded="full"
            icon="heroicon-o-arrow-left"
            icon-position="leading"
            wire:click="previousStep"
            wire:loading.attr="disabled"
        >
            Voltar
        </x-he4rt::button>
    @else
        <span class="text-text-medium font-mono text-xs">da comunidade, para a comunidade</span>
    @endif

    <x-he4rt::button
        variant="solid"
        rounded="full"
        icon="heroicon-o-arrow-right"
        icon-position="trailing"
        wire:click="nextStep"
        wire:loading.attr="disabled"
        wire:target="nextStep"
    >
        {{ $isLast ? 'Criar meu perfil' : 'Continuar' }}
    </x-he4rt::button>
</div>
