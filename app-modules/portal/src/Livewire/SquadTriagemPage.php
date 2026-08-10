<?php

declare(strict_types=1);

namespace He4rt\Portal\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout(name: 'portal::components.layouts.app')]
#[Title(content: 'Triagem dos Squads')]
final class SquadTriagemPage extends Component
{
    /** Etapas do fluxo de triagem, na ordem em que são apresentadas. */
    private const array STEPS = ['Perfil', 'Skills & rotina', 'Preferências'];

    /** Campos validados em cada etapa antes de liberar o avanço. */
    private const array STEP_FIELDS = [
        0 => ['nome', 'discord', 'areasInteresse', 'nivelTecnico'],
        1 => ['tecnologias', 'tempoSemana', 'gitExperiencia'],
        2 => ['participarProjetos', 'dispDesafios', 'divulgarComunidade', 'preferenciaAtuacao'],
    ];

    public int $step = 0;

    public bool $submitted = false;

    // Etapa 1 — Perfil
    public string $nome = '';
    public string $discord = '';

    /** @var list<string> */
    public array $areasInteresse = [];
    public ?string $nivelTecnico = null;

    // Etapa 2 — Skills & rotina
    /** @var list<string> */
    public array $tecnologias = [];
    public string $tempoSemana = '';
    public string $gitExperiencia = '';

    // Etapa 3 — Preferências
    public ?string $participarProjetos = null;
    public ?string $dispDesafios = null;
    public ?string $divulgarComunidade = null;

    /** @var list<string> */
    public array $preferenciaAtuacao = [];

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'nome' => 'required|string|max:80',
            'discord' => 'required|string|max:40',
            'areasInteresse' => 'required|array|min:1',
            'nivelTecnico' => 'required|in:iniciante,intermediario,avancado',
            'tecnologias' => 'required|array|min:1',
            'tempoSemana' => 'required|in:menos-2h,2-5h,5-10h,mais-10h',
            'gitExperiencia' => 'required|in:diaadia,pouco,nao',
            'participarProjetos' => 'required|in:sim,talvez,nao',
            'dispDesafios' => 'required|in:sim,talvez,nao',
            'divulgarComunidade' => 'nullable|in:sim,talvez,nao',
            'preferenciaAtuacao' => 'required|array|min:1',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'nome.required' => 'Conta pra gente como te chamar.',
            'discord.required' => 'Precisamos do seu usuário pra te encontrar no Discord.',
            'areasInteresse.required' => 'Escolha ao menos uma área de interesse.',
            'areasInteresse.min' => 'Escolha ao menos uma área de interesse.',
            'nivelTecnico.required' => 'Selecione o seu nível técnico percebido.',
            'nivelTecnico.in' => 'Selecione o seu nível técnico percebido.',
            'tecnologias.required' => 'Marque pelo menos uma tecnologia que você conhece.',
            'tecnologias.min' => 'Marque pelo menos uma tecnologia que você conhece.',
            'tempoSemana.required' => 'Escolha quanto tempo você tem por semana.',
            'gitExperiencia.required' => 'Esse campo é obrigatório para a triagem.',
            'participarProjetos.required' => 'Diz pra gente seu interesse em projetos.',
            'dispDesafios.required' => 'Selecione sua disponibilidade para desafios.',
            'preferenciaAtuacao.required' => 'Escolha ao menos uma preferência de atuação.',
            'preferenciaAtuacao.min' => 'Escolha ao menos uma preferência de atuação.',
        ];
    }

    /**
     * Alterna um valor dentro de um campo de múltipla escolha (chips).
     */
    public function toggle(string $field, string $value): void
    {
        if (!in_array($field, ['areasInteresse', 'tecnologias', 'preferenciaAtuacao'], true)) {
            return;
        }

        /** @var list<string> $values */
        $values = $this->{$field};

        $this->{$field} = in_array($value, $values, true)
            ? array_values(array_filter($values, static fn (string $item): bool => $item !== $value))
            : [...$values, $value];

        $this->resetErrorBag($field);
    }

    public function nextStep(): void
    {
        $this->validate(
            collect(self::STEP_FIELDS[$this->step] ?? [])
                ->mapWithKeys(fn (string $field): array => [$field => $this->rules()[$field]])
                ->all(),
        );

        if ($this->step < count(self::STEPS) - 1) {
            $this->step++;

            return;
        }

        $this->submitted = true;
    }

    public function previousStep(): void
    {
        $this->resetErrorBag();
        $this->step = max(0, $this->step - 1);
    }

    public function resetForm(): void
    {
        $this->reset();
    }

    public function render(): View
    {
        return view('portal::livewire.squad-triagem-page', [
            'steps' => self::STEPS,
            'progress' => (int) round((($this->step + 1) / count(self::STEPS)) * 100),
            'areas' => ['Dev', 'Design', 'Produto', 'Conteúdo', 'Dados'],
            'niveis' => [
                ['value' => 'iniciante', 'label' => 'Iniciante'],
                ['value' => 'intermediario', 'label' => 'Intermediário'],
                ['value' => 'avancado', 'label' => 'Avançado'],
            ],
            'tecnologiasDisponiveis' => [
                'JavaScript', 'TypeScript', 'React', 'Node.js', 'PHP / Laravel',
                'Python', 'Go', 'Rust', 'SQL', 'Docker',
            ],
            'tempos' => [
                ['value' => 'menos-2h', 'label' => 'Menos de 2h'],
                ['value' => '2-5h', 'label' => '2 a 5 horas'],
                ['value' => '5-10h', 'label' => '5 a 10 horas'],
                ['value' => 'mais-10h', 'label' => 'Mais de 10h'],
            ],
            'gits' => [
                ['value' => 'diaadia', 'label' => 'Uso no dia a dia'],
                ['value' => 'pouco', 'label' => 'Uso pouco'],
                ['value' => 'nao', 'label' => 'Ainda não sei usar'],
            ],
            'simNaoTalvez' => [
                ['value' => 'sim', 'label' => 'Sim'],
                ['value' => 'talvez', 'label' => 'Talvez'],
                ['value' => 'nao', 'label' => 'Não'],
            ],
            'preferencias' => ['Código', 'Design', 'Organização', 'Conteúdo', 'Suporte'],
            'resumo' => $this->resumo(),
            'precisaOnboardingGit' => $this->gitExperiencia !== 'diaadia',
        ]);
    }

    /**
     * Monta o resumo do perfil exibido na tela de conclusão.
     *
     * @return list<array{label: string, value: string}>
     */
    private function resumo(): array
    {
        $niveis = ['iniciante' => 'Iniciante', 'intermediario' => 'Intermediário', 'avancado' => 'Avançado'];
        $tempos = ['menos-2h' => 'Menos de 2h', '2-5h' => '2 a 5 horas', '5-10h' => '5 a 10 horas', 'mais-10h' => 'Mais de 10h'];
        $gits = ['diaadia' => 'Uso no dia a dia', 'pouco' => 'Uso pouco', 'nao' => 'Ainda estou aprendendo'];

        $stack = collect($this->tecnologias);
        $stackLabel = $stack->take(4)->implode(', ');
        if ($stack->count() > 4) {
            $stackLabel .= ' +' . ($stack->count() - 4);
        }

        return [
            ['label' => 'Áreas', 'value' => implode(', ', $this->areasInteresse) ?: '—'],
            ['label' => 'Nível', 'value' => $niveis[$this->nivelTecnico] ?? '—'],
            ['label' => 'Stack', 'value' => $stackLabel ?: '—'],
            ['label' => 'Tempo/semana', 'value' => $tempos[$this->tempoSemana] ?? '—'],
            ['label' => 'Atuação', 'value' => implode(', ', $this->preferenciaAtuacao) ?: '—'],
            ['label' => 'Git/GitHub', 'value' => $gits[$this->gitExperiencia] ?? '—'],
        ];
    }
}
