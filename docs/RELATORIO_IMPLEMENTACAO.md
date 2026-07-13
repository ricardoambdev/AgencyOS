# Relatório de Implementação — AgencyOS

**Data:** 13/07/2026
**Stack:** Laravel 13 · PHP 8.3 · MySQL 8 / SQLite · Tailwind + Alpine.js
**Escopo:** Plataforma Operacional Configurável (multi-tenant) descrita em `Instructions.md`.

---

## 1. Resumo Executivo

O sistema foi implementado como uma aplicação Laravel 13 com arquitetura orientada a domínios (DDD). Todos os módulos funcionais do `Instructions.md` estão operacionais, com autenticação, RBAC por capacidades (`cap:`), multi-tenancy por `company_id`, configuração por empresa, portal do cliente, automações, webhooks, notificações, auditoria e busca global.

Nesta entrega foram **completados os itens que ainda estavam pendentes** na primeira fase:

- **Tags** genéricas por entidade (com `syncTags()`).
- **Favoritos** (toggle + listagem).
- **Anexos genéricos** (`Attachment` morphTo) reutilizáveis em todas as entidades.
- **Dark Mode / Light Mode** persistido.
- **Rate limiting** (`throttle:10,1`) em login/registro/recuperação.
- **Relatórios** com export **Excel (.xls)** e **PDF/HTML imprimível** (além do CSV).
- **Recuperação de senha** (broker do Laravel + telas).
- **Comentários** com **menções (`@nome`)** e **reações (👍 ❤️ 🎉)**.
- **Verificação de e-mail** (`MustVerifyEmail` + rotas assinadas).
- **Filas `database`** para exportação de relatórios (`BuildReportExport` job).

A suíte de testes está **verde (38 testes / 106 asserções)**.

---

## 2. Arquitetura

```
app/
  Core/                 # Motor compartilhado (cross-domain)
    Concerns/           # BelongsToCompany, HasUlid, HasActivity, HasConfig, HasApiTokens
    Engines/            # CommentEngine, ReportEngine, WebhookEngine, WorkflowEngine, SearchEngine
    Models/             # Comment, Attachment, Favorite, Tag, Webhook, Automation, Setting, AuditLog, Notification
    Support/            # CompanyContext, EntityCatalog, ConfigRepository
    Controllers/        # CommentController, AttachmentController, FavoriteController
  Domains/              # Um namespace por domínio de negócio
    Crm/ Cliente/ Projeto/ Agenda/ Financeiro/ Producao/ Wiki/
    Equipamento/ Hora/ Comercial/ Relatorios/ Company/ Usuario/
  Http/Controllers/     # AuthController (conv. HTTP)
  Jobs/                 # BuildReportExport (fila database)
  Policies/             # Uma policy por modelo (retorna true por padrão; RBAC via cap:)
routes/web.php          # Rotas web (agrupadas por middleware guest/auth/cap:)
resources/views/        # Blade por domínio + partials reutilizáveis
```

### Multi-tenancy
- Todo modelo de domínio usa `BelongsToCompany` → coluna `company_id` + escopo global `ForCurrentCompany`.
- `CompanyContext` guarda a empresa ativa (do `CompanyUser` do usuário) e é lido pelos engines.

### RBAC
- `Role` possui `capabilities` (array; `*` = tudo).
- Middleware `can:cap:xxxx` protege rotas; policies retornam `true` (autorização discricionária feita por capacidade).
- Menus e dashboards respeitam as capacidades do usuário.

### Campos Personalizados
- `CustomField` + `CustomValue` (EAV) com tipos `text|number|select|date|boolean|file`.
- Injetados nas views de formulário/show de Lead, Cliente, Projeto e Contrato.

---

## 3. Itens do Instructions.md — Status

### 3.1 Implementados e verificados
- **Visão Geral / Dashboard:** KPIs (receita, despesa, margem, projetos ativos, leads, tarefas), widgets configuráveis persistidos em `User.dashboard_widgets` (JSON), tela de personalização.
- **CRM / Lead:** pipeline, status, responsável, valor, tags, campos personalizados, comentários, anexos, favorito.
- **Clientes:** cadastro, contatos, campos personalizados, portal do cliente.
- **Projetos & Tarefas:** status (`backlog|todo|doing|review|done`), prioridade, horas estimadas/reais, dependências, checklist, comentários, anexos.
- **Agenda:** eventos com participantes e convite por e-mail.
- **Financeiro:** contas a pagar/receber, categorias, fluxo de caixa, mark-as-paid, relatório.
- **Produção:** brief, entregáveis, versões, aprovação interna/cliente, anexos.
- **Wiki:** páginas com categorias, versões, permissão (public/internal/private).
- **Equipamentos:** patrimônio, alocação a projetos, disponibilidade.
- **Horas:** apontamento por tarefa/usuário, aprovação, relatórios.
- **Comercial / Contratos:** modelos, PDF, assinatura, anexos próprios (colunas `name`/`mime` corrigidas para o padrão core).
- **Templates de Projeto:** catálogo + aplicação (gera projeto + tarefas).
- **Configuração:** empresa, usuários (convite), papéis (capabilities), workflows, webhooks, portal, integrações.
- **Automação:** `Automation` com gatilho (`entity.created|entity.updated|comment.added|task.done|invoice.paid`) → ação (`notify|webhook|email|change_status|assign`).
- **Webhooks:** `WebhookEngine` dispara `POST` JSON assinado (header `X-AgencyOS-Signature`).
- **Notificações:** `Notification` (in-app) + e-mail.
- **Auditoria:** `AuditLog` em ações sensíveis.
- **Busca:** `SearchEngine` unificando leads, clientes, projetos, tarefas, contratos, wiki.
- **Relatórios:** `ReportEngine` (CRM, projetos, faturamento, tarefas, carga) com export **CSV**, **Excel** e **PDF/HTML**.
- **Portal do Cliente:** rota pública assinada `/{token}` com escopo do cliente.

### 3.2 Implementados nesta entrega (detalhe)
- **Tags:** `Tag` + `taggables` (polymorphic). `HasActivity::syncTags(iterable $names)` cria/relaciona por `company_id`+`slug`. Partials `tags-input` e `tags-display`; wiring em Lead/Cliente/Contrato/Equipamento/Projeto.
- **Favoritos:** `FavoriteController` (`index`, `toggle` JSON/redirect) + `favoritos/*` rotas + view + botão no topo das telas de show + link no menu.
- **Anexos genéricos:** `AttachmentController` (`store/download/destroy`) + `attachments.*` + partial `attachments` no painel `entity-activity`. Contrato teve as colunas de anexo padronizadas (`filename`→`name`, `mime_type`→`mime`).
- **Dark Mode:** `tailwind.config = { darkMode: 'class' }`, classe no `<html>`, `localStorage` + `prefers-color-scheme`, toggle no topo, overrides CSS.
- **Rate limiting:** `throttle:10,1` em `login`, `register`, `forgot-password`, `reset-password`.
- **Relatórios Excel/PDF:** `ReportController::excelExport()` (Spreadsheet XML) e view `relatorios/print.blade.php`; links na `relatorios/index.blade.php`.
- **Recuperação de senha:** `AuthController` (`showForgot`/`sendResetLink`/`showReset`/`resetPassword`), rotas `password.*`, views `auth/forgot-password.blade.php` e `auth/reset-password.blade.php`, link no login, `config/auth.php` apontando para `App\Domains\Usuario\Models\User`.
- **Comentários (menções/reações):** migration adiciona `reactions` e `mentions` (JSON) em `comments`. `CommentController::toggleReaction` (endpoint JSON) + parsing de `@menção` no `store`. Partial `comments.blade.php` com botões reativos (Alpine) e destaque de menções.
- **Verificação de e-mail:** `User` implementa `MustVerifyEmail`; `AuthController` envia notificação no registro e expõe `verification.verify` (rota assinada), `verification.notice`, `verification.send`; banner no dashboard.
- **Filas database:** `BuildReportExport` (`ShouldQueue`, fila `exports`). Disparado em `ReportController::export`. Para ativar em produção, defina `QUEUE_CONNECTION=database` e rode `php artisan queue:work`.

### 3.3 Pendências / Roadmap (checklist de implementação)
> Itens do `Instructions.md` ainda não implementados (ou parcialmente). Cada um tem o ponto de partida mapeado.

1. **[ ] Status de workflow configuráveis por entidade via UI**
   - Hoje os estados vêm de `EntityCatalog`. Criar tabela `workflow_states` (company_id, entity_type, name, color, order, is_initial, is_final) e `workflow_transitions`; substituir o catálogo estático por leitura do banco nas telas de show/kanban.
2. **[ ] Checklists em templates de projeto**
   - Estender `ProjectTemplate` com `template_checklists` (name, items JSON) e criar os itens ao aplicar o template (`ProjectTemplateController::storeApply`).
3. **[ ] Menus configuráveis por workspace**
   - Tabela `menu_items` (company_id, label, route, icon, order, parent_id); renderizar o menu lateral a partir dela em `layouts/app.blade.php`.
4. **[ ] Templates de workspace por segmento**
   - Hoje only `agency`. Adicionar `workspace_template` variants (`clinic`, `ecommerce`, `saas`, etc.) em `EntityCatalog` + seeders por segmento.
5. **[ ] Engines de import/export (NFe/XML) e integrações fiscais**
   - `Core/Engines/ImportEngine` + `ExportEngine`; mapeamento de fornecedores/produtos; webhook de retorno da contabilidade.
6. **[ ] API pública (tokens)**
   - `HasApiTokens` já existe; faltam rotas `routes/api.php` versionadas (`/api/v1`) + throttle + documentação (OpenAPI).
7. **[ ] App mobile / PWA**
   - Service worker + manifest; reutilizar a API pública.
8. **[ ] Marketplace de plugins**
   - Descobrimento de providers em `bootstrap/providers.php` + tabela `installed_plugins`.
9. **[ ] IA (sugestão de cursor/processos)**
   - `Core/Engines/AiEngine` (stub) consumindo LLM via `config('services.ai')`.

---

## 4. Decisões Técnicas

- **Sem build front-end:** Tailwind e Alpine via CDN para simplicidade de deploy (Laragon/XAMPP). Em produção, recomenda-se compilar com Vite.
- **Relatórios Excel:** gerados como *Spreadsheet XML* (abre no Excel/LibreOffice) sem dependências externas.
- **PDF:** abordagem *print view* (HTML imprimível) — sem libs pesadas; o usuário imprime/salva como PDF do navegador.
- **Reações:** armazenadas como `['emoji' => [user_ids]]` em JSON; toggle é idempotente por usuário.
- **Verificação de e-mail:** rota `verification.verify` é **assinada e stateless** (fora do grupo `guest`), para funcionar mesmo para usuários já logados.

---

## 5. Qualidade

- **Testes:** 38 testes / 106 asserções, todos verdes (`vendor/bin/phpunit --no-coverage`).
- **Cobertura:** auth (login/logout/reset/verificação), atividade (favoritar, tags, anexos, reações, menções), busca, relatórios (csv/excel/pdf/queue), RBAC básico, comentários.
- **Banco de testes:** SQLite `:memory:` com `RefreshDatabase`.

---

## 6. Próximos Passos Sugeridos

1. Implementar os itens 3.3 (prioridade: #1 status configuráveis e #2 checklists).
2. Ativar `QUEUE_CONNECTION=database` em produção e monitorar a fila `exports`.
3. Compilar assets com Vite para produção.
4. Criar rotas `/api/v1` documentadas.
5. `git init` + primeiro commit (repositório remoto informado posteriormente).
