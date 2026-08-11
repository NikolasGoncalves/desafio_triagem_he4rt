# Triagem dos Squads

Documentação da feature **Triagem dos Squads** — o formulário multi-etapas que serve como porta de entrada de novos membros para os Squads da comunidade He4rt Developers.

## Onde fica no código

| O quê | Arquivo |
|---|---|
| Rota | `app-modules/portal/src/PortalServiceProvider.php` → `Route::get('/', SquadTriagemPage::class)->name('home')` |
| Componente Livewire | `app-modules/portal/src/Livewire/SquadTriagemPage.php` |
| View principal | `app-modules/portal/resources/views/livewire/squad-triagem-page.blade.php` |
| Subcomponentes | `app-modules/portal/resources/views/components/triagem/*.blade.php` |

A página é a **home** (`/`) da aplicação — não é uma rota secundária.

## O que a feature faz

Um wizard de 3 passos, todo controlado no servidor via Livewire (sem JS de estado próprio), que coleta o perfil de quem quer entrar em um Squad e termina em uma tela de conclusão com resumo + próximo passo sugerido.

### Etapas e campos

| Etapa | Label | Campos | Regras (`rules()`) |
|---|---|---|---|
| 0 | Perfil | `nome`, `discord`, `areasInteresse[]`, `nivelTecnico` | nome/discord obrigatórios (string, max 80/40); `areasInteresse` array com min 1; `nivelTecnico` em `iniciante,intermediario,avancado` |
| 1 | Skills & rotina | `tecnologias[]`, `tempoSemana`, `gitExperiencia` | `tecnologias` array min 1; `tempoSemana` em `menos-2h,2-5h,5-10h,mais-10h`; `gitExperiencia` em `diaadia,pouco,nao` |
| 2 | Preferências | `participarProjetos`, `dispDesafios`, `divulgarComunidade`, `preferenciaAtuacao[]` | `participarProjetos`/`dispDesafios` em `sim,talvez,nao` (obrigatórios); `divulgarComunidade` igual, mas **opcional**; `preferenciaAtuacao` array min 1 |

A validação é por etapa: `nextStep()` valida apenas os campos definidos em `STEP_FIELDS[$this->step]` antes de avançar (ou marcar `submitted = true` na última etapa). `previousStep()` limpa os erros e volta uma etapa; nada é revalidado ao voltar.

### Opções exibidas (vêm do `render()`, não são hardcoded na view)

- **Áreas de interesse:** Dev, Design, Produto, Conteúdo, Dados
- **Tecnologias:** JavaScript, TypeScript, React, Node.js, PHP / Laravel, Python, Go, Rust, SQL, Docker
- **Tempo por semana:** menos de 2h · 2 a 5h · 5 a 10h · mais de 10h
- **Experiência com Git:** dia a dia · pouco · ainda não sei usar
- **Preferência de atuação:** Código, Design, Organização, Conteúdo, Suporte

### Interação "chips" (multi-seleção)

Campos de múltipla escolha (`areasInteresse`, `tecnologias`, `preferenciaAtuacao`) não usam um input nativo — são botões renderizados pelo componente `<x-portal::triagem.chip-group>`, que chamam `wire:click="toggle('campo', valor)"`. O método `toggle()` no componente Livewire adiciona/remove o valor do array e limpa o erro daquele campo especificamente (`resetErrorBag($field)`).

### Tela de conclusão

Quando `submitted === true`, a view troca o formulário pelo componente `<x-portal::triagem.success-screen>`, que:
- Monta um resumo (`resumo()`) com Áreas, Nível, Stack (até 4 tecnologias + contador do restante), Tempo/semana, Atuação e experiência com Git.
- Decide a mensagem do "próximo passo" com base em `precisaOnboardingGit` (`true` quando `gitExperiencia !== 'diaadia'`), sugerindo preparar o ambiente antes do desafio de Git ou já partir direto para ele.
- Oferece um botão para refazer a triagem, que chama `resetForm()` (reseta todas as public properties do componente).

## Ponto de atenção: persistência

No estado atual do código, **os dados da triagem não são salvos em banco**. `SquadTriagemPage` mantém tudo como *public properties* do componente Livewire e, ao final, apenas seta `$submitted = true` — não há `Model`, `migration` ou chamada de `save()`/dispatch de evento associada a essa feature. Isso é relevante caso o objetivo seja usar essas respostas para triagem real (hoje elas se perdem ao fim da sessão/reload).

## Componentes Blade reutilizados

- `x-portal::triagem.field` — wrapper de label + hint + slot + erro (usa `role="alert"` na mensagem de erro).
- `x-portal::triagem.chip-group` — grupo de botões toggle (`role="group"`, `aria-pressed`).
- `x-portal::triagem.progress-stepper` — indicador de etapas + barra de progresso (`aria-current="step"` na etapa ativa).
- `x-portal::triagem.form-navigation` — botões Voltar/Continuar (o rótulo do botão de avançar muda para "Criar meu perfil" na última etapa).
- `x-portal::triagem.info-callout` — aviso contextual (ex.: exibido quando `gitExperiencia === 'nao'`).
- `x-portal::triagem.success-screen` + `x-portal::triagem.review-card` — tela e cards do resumo final.

Todos usam os componentes de design system do próprio projeto (`x-he4rt::button`, `x-he4rt::heading`, `x-he4rt::text`, `x-he4rt::headline`) e inputs do **Flux UI** (`flux:input`, `flux:select`, `flux:radio.group`).

## Testes

Os demais recursos do módulo `portal` têm testes de feature em `app-modules/portal/tests/Feature/` (ex.: `HomepageTest.php`, `SocialLinksPageTest.php`). **Não há, hoje, um `SquadTriagemPageTest`** cobrindo o fluxo de triagem — vale considerar ao trabalhar nessa feature, especialmente para cobrir: validação por etapa, `toggle()`, navegação entre passos e o cálculo de `resumo()`/`precisaOnboardingGit`.

## Rodando localmente

Segue o fluxo padrão do monorepo (via `Makefile`, na raiz do projeto):

```bash
make env-up   # sobe Postgres/Redis via Docker
make setup    # instala deps PHP/Node, gera key, linka storage
make dev      # sobe servidor Laravel + Vite
```

A página fica disponível em `/` (rota nomeada `home`).

```bash
make test     # roda a suíte Pest
make pint     # formata o código
make phpstan  # análise estática
```
