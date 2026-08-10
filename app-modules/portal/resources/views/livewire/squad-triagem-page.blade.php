<div class="triagem-page relative min-h-[calc(100svh-6rem)] overflow-x-hidden">
    <style>
        .triagem-page [data-flux-input-group-prefix] {
            border-start-start-radius: 0.5rem;
            border-end-start-radius: 0.5rem;
            background-color: rgb(0 0 0 / 0.02);
        }
        :root.dark .triagem-page [data-flux-input-group-prefix] {
            background-color: rgb(255 255 255 / 0.15);
        }
        :root.dark .triagem-page [data-flux-select-native] {
        background-color: rgb(255 255 255 / 0.1);
        border-color: rgb(255 255 255 / 0.1);
        color: #d4d4d8;
        }

        :root.dark .triagem-page [data-flux-select-native] option {
            background-color: #3f3f46;
            color: #fff;
        }
    </style>
    {{-- Brilho ambiente da marca --}}
    <div
        aria-hidden="true"
        class="pointer-events-none fixed inset-0 -z-10"
        style="
            background:
                radial-gradient(600px 400px at 85% -5%, color-mix(in srgb, var(--primary) 18%, transparent), transparent 70%),
                radial-gradient(500px 400px at 5% 15%, color-mix(in srgb, var(--secondary) 12%, transparent), transparent 70%);
        "
    ></div>
<div
    class="pointer-events-none fixed inset-0 -z-10 flex items-center justify-center overflow-hidden p-8 opacity-40 sm:p-16"
    aria-hidden="true"
>
    <x-portal::animated-logo class="w-full max-w-5xl" />
</div>
<main class="relative z-10 mx-auto max-w-5xl px-4 pb-24 sm:px-6">
        {{-- Hero --}}
        <section class="relative pt-14 pb-10 sm:pt-10">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-primary font-mono text-sm uppercase tracking-[0.18em]">Triagem</p>

                <x-he4rt::headline size="lg" :keywords="['porta', 'de', 'entrada']">
                    <x-slot:title class="whitespace-nowrap">A porta de entrada dos Squads</x-slot:title>
                </x-he4rt::headline>

                <x-he4rt::text class="text-text-medium mx-auto mt-5 max-w-xl text-pretty leading-relaxed">
                    Este formulário é o primeiro passo da sua jornada. Em apenas três etapas, vamos conhecer seu
                    perfil e indicar os desafios e squads que mais combinam com você.
                </x-he4rt::text>
            </div>
        </section>

        {{-- Formulário / Conclusão --}}
        <section id="triagem" class="scroll-mt-24">
            @if ($submitted)
                <x-portal::triagem.success-screen
                    :nome="$nome"
                    :resumo="$resumo"
                    :precisa-onboarding-git="$precisaOnboardingGit"
                />
            @else
                <div class="mx-auto max-w-2xl" >
                    <form
                        wire:submit="nextStep"
                        class="bg-elevation-surface/55 ring-1 ring-zinc-950/8 dark:ring-white/10 shadow-md shadow-zinc-950/8 
                        dark:shadow-black/20 backdrop-blur-lg overflow-hidden rounded-2xl transition-all duration-300"
                    >
                        {{-- Stepper + progresso --}}
                        <div class="px-5 pt-5 sm:px-6">
                            <x-portal::triagem.progress-stepper
                                :steps="$steps"
                                :current="$step"
                                :progress="$progress"
                            />
                        </div>

                        {{-- Campos --}}
                        <div class="space-y-6 p-5 sm:p-6">
                            {{-- Etapa 1 — Perfil --}}
                            @if ($step === 0)
                                <x-portal::triagem.field
                                    label="Como podemos te chamar?"
                                    for="nome"
                                    required
                                    :error="$errors->first('nome')"
                                >
                                    <flux:input id="nome" wire:model.blur="nome" placeholder="Seu nome ou apelido" />
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Usuário do Discord"
                                    for="discord"
                                    required
                                    :error="$errors->first('discord')"
                                >
                                <flux:input.group>
                                    <flux:input.group.prefix>@</flux:input.group.prefix>
                                    <flux:input
                                        id="discord"
                                        wire:model.blur="discord"
                                        placeholder="seu_usuario"
                                        class:input="rounded-s-none border-s-0"
                                    />
                                </flux:input.group>
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Área de interesse"
                                    required
                                    hint="Pode escolher mais de uma."
                                    :error="$errors->first('areasInteresse')"
                                >
                                    <x-portal::triagem.chip-group
                                        field="areasInteresse"
                                        :options="$areas"
                                        :selected="$areasInteresse"
                                    />
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Nível técnico percebido"
                                    required
                                    :error="$errors->first('nivelTecnico')"
                                >
                                    <flux:radio.group wire:model.live="nivelTecnico" variant="segmented" class="gap-2">
                                        @foreach ($niveis as $nivel)
                                            <flux:radio
                                                :value="$nivel['value']"
                                                :label="$nivel['label']"
                                                class="data-checked:bg-[var(--primary)] data-checked:text-white data-checked:border-none data-checked:shadow-sm bg-transparent text-text-medium border border-zinc-900/20"
                                            />
                                        @endforeach
                                    </flux:radio.group>
                                </x-portal::triagem.field>
                            @endif

                            {{-- Etapa 2 — Skills & rotina --}}
                            @if ($step === 1)
                                <x-portal::triagem.field
                                    label="Tecnologias que você conhece"
                                    required
                                    hint="Marque as que você já usou, sem pressão."
                                    :error="$errors->first('tecnologias')"
                                >
                                    <x-portal::triagem.chip-group
                                        field="tecnologias"
                                        :options="$tecnologiasDisponiveis"
                                        :selected="$tecnologias"
                                    />
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Tempo disponível por semana"
                                    for="tempo"
                                    required
                                    :error="$errors->first('tempoSemana')"
                                >
                                    <flux:select id="tempo" wire:model.live="tempoSemana" placeholder="Selecione uma faixa">
                                        @foreach ($tempos as $tempo)
                                            <flux:select.option :value="$tempo['value']">{{ $tempo['label'] }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Experiência com Git/GitHub"
                                    for="git"
                                    required
                                    :error="$errors->first('gitExperiencia')"
                                >
                                    <flux:select id="git" wire:model.live="gitExperiencia" placeholder="Selecione uma opção">
                                        @foreach ($gits as $git)
                                            <flux:select.option :value="$git['value']">{{ $git['label'] }}</flux:select.option>
                                        @endforeach
                                    </flux:select>

                                    @if ($gitExperiencia === 'nao')
                                        <x-portal::triagem.info-callout>
                                            Sem problema — a triagem é aberta a quem está começando. Deixamos
                                            documentação e um vídeo de referência pra você preparar o ambiente antes
                                            do desafio de Git.
                                        </x-portal::triagem.info-callout>
                                    @endif
                                </x-portal::triagem.field>
                            @endif

                            {{-- Etapa 3 — Preferências --}}
                            @if ($step === 2)
                                <x-portal::triagem.field
                                    label="Interesse em participar de projetos"
                                    required
                                    :error="$errors->first('participarProjetos')"
                                >
                                    <flux:radio.group wire:model.live="participarProjetos" variant="segmented" class="gap-2">
                                        @foreach ($simNaoTalvez as $opcao)
                                            <flux:radio
                                                :value="$opcao['value']"
                                                :label="$opcao['label']"
                                                class="data-checked:bg-[var(--primary)] data-checked:text-white data-checked:border-none data-checked:shadow-sm bg-transparent text-text-medium border border-zinc-700/20"
                                            />
                                        @endforeach
                                    </flux:radio.group>
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Disponibilidade para desafios"
                                    required
                                    :error="$errors->first('dispDesafios')"
                                >
                                    <flux:radio.group wire:model.live="dispDesafios" variant="segmented" class="gap-2">
                                        @foreach ($simNaoTalvez as $opcao)
                                            <flux:radio
                                                :value="$opcao['value']"
                                                :label="$opcao['label']"
                                                class="data-checked:bg-[var(--primary)] data-checked:text-white data-checked:border-none data-checked:shadow-sm bg-transparent text-text-medium border border-zinc-700/20"
                                            />
                                        @endforeach
                                    </flux:radio.group>
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Interesse em divulgar a comunidade"
                                    optional
                                    hint="Compartilhar os Squads nas suas redes."
                                >
                                    <flux:radio.group wire:model.live="divulgarComunidade" variant="segmented" class="gap-2">
                                        @foreach ($simNaoTalvez as $opcao)
                                            <flux:radio
                                                :value="$opcao['value']"
                                                :label="$opcao['label']"
                                                class="data-checked:bg-[var(--primary)] data-checked:text-white data-checked:border-none data-checked:shadow-sm bg-transparent text-text-medium border border-zinc-700/20"
                                            />
                                        @endforeach
                                    </flux:radio.group>
                                </x-portal::triagem.field>

                                <x-portal::triagem.field
                                    label="Preferência de atuação"
                                    required
                                    hint="Onde você curte colaborar no dia a dia."
                                    :error="$errors->first('preferenciaAtuacao')"
                                >
                                    <x-portal::triagem.chip-group
                                        field="preferenciaAtuacao"
                                        :options="$preferencias"
                                        :selected="$preferenciaAtuacao"
                                    />
                                </x-portal::triagem.field>
                            @endif
                        </div>

                        {{-- Ações --}}
                        <x-portal::triagem.form-navigation
                            :current="$step"
                            :is-last="$step === count($steps) - 1"
                        />
                    </form>

                    <p class="text-text-medium mt-4 text-center font-mono text-xs">
                        Seus dados montam o perfil de entrada nos Squads · nada é compartilhado fora da He4rt
                    </p>
                </div>
            @endif
        </section>
    </main>

    <footer class="border-outline-low border-t">
        <div class="mx-auto flex max-w-5xl flex-col items-center gap-2 px-4 py-4 text-center sm:px-6">
            <p class="text-text-medium text-xs">
                Comunidade sem fins lucrativos · feito com carinho pela He4rt Developers
            </p>
        </div>
    </footer>
</div>
