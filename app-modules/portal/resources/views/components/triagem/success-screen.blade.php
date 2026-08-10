@props([
    'nome' => '',
    'resumo' => [],
    'precisaOnboardingGit' => true,
])

@php
    $primeiroNome = trim(explode(' ', $nome)[0] ?? '') ?: 'dev';
@endphp

<div class="mx-auto max-w-2xl">
    <div class="border-outline-medium bg-elevation-02dp overflow-hidden rounded-2xl border">
        {{-- Cabeçalho --}}
        <div class="border-outline-low bg-primary/10 flex flex-col items-center gap-4 border-b px-6 py-10 text-center">
            <div class="from-primary to-secondary flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br shadow-lg">
                <x-heroicon-s-check-circle class="text-text-light h-8 w-8" />
            </div>
            <div class="space-y-2">
                <x-he4rt::heading :level="2" size="lg" class="text-balance">
                    Perfil criado, {{ $primeiroNome }}!
                </x-he4rt::heading>
                <x-he4rt::text size="sm" class="text-text-medium text-pretty">
                    Sua triagem foi registrada. Agora falta um passo pra você entrar de vez nos Squads.
                </x-he4rt::text>
            </div>
        </div>

        {{-- Resumo do perfil --}}
        <div class="bg-outline-low grid gap-px sm:grid-cols-2">
            @foreach ($resumo as $item)
                <x-portal::triagem.review-card :label="$item['label']" :value="$item['value']" />
            @endforeach
        </div>

        {{-- Próximo passo --}}
        <div class="space-y-4 p-6">
            <div class="text-primary flex items-center gap-2 font-mono text-xs uppercase tracking-wider">
                <x-heroicon-o-code-bracket class="h-4 w-4" />
                Próximo passo
            </div>

            <x-he4rt::heading :level="3" size="sm">
                {{ $precisaOnboardingGit ? 'Prepare o ambiente pro desafio de Git' : 'Bora pro desafio de Git' }}
            </x-he4rt::heading>

            <x-he4rt::text size="sm" class="text-text-medium leading-relaxed">
                {{ $precisaOnboardingGit
                    ? 'Sem problema começar do zero — deixamos a documentação e um vídeo de referência pra você configurar o Git e o GitHub antes de abrir seu primeiro Pull Request.'
                    : 'Você já domina o Git. O próximo passo é clonar o repositório da comunidade e abrir seu primeiro Pull Request de boas-vindas.' }}
            </x-he4rt::text>

            {{-- Bloco de terminal, no mesmo estilo do x-portal::terminal --}}
            <div class="overflow-hidden rounded-lg bg-gray-900 font-mono text-[13px]">
                <div class="border-b border-gray-800 px-4 py-2 text-gray-500">próximo-passo.sh</div>
                <div class="space-y-1 p-4">
                    <p class="text-gray-100">
                        <span class="text-green-400">➜</span> git clone
                        <span class="text-cyan-300">he4rt/desafio-boas-vindas</span>
                    </p>
                    <p class="text-gray-100">
                        <span class="text-green-400">➜</span> git checkout -b
                        <span class="text-gray-400">primeiro-pull-request</span>
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-1 sm:flex-row">
                <x-he4rt::button
                    href="https://discord.gg/he4rt"
                    target="_blank"
                    variant="solid"
                    size="lg"
                    rounded="full"
                    icon="heroicon-o-arrow-right"
                    icon-position="trailing"
                    class="flex-1"
                >
                    Ir para o desafio de Git
                </x-he4rt::button>

                <x-he4rt::button
                    variant="outline"
                    size="lg"
                    rounded="full"
                    icon="heroicon-o-arrow-path"
                    icon-position="leading"
                    wire:click="resetForm"
                >
                    Refazer triagem
                </x-he4rt::button>
            </div>
        </div>
    </div>
</div>
