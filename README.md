# AgencyOS — Plataforma Operacional Configurável

Plataforma SaaS multi-tenant para agências e estúdios, construída em **Laravel 13** (PHP 8.3) com arquitetura orientada a domínios (DDD) e multi-tenancy por `company_id`.

> Sistema operacional de agência: CRM, gestão de clientes, projetos, tarefas, financeiro, produção, wiki, equipamentos, horas, comercial/contratos, relatórios, portal do cliente, automações, webhooks, notificações, auditoria e busca global — tudo configurável por empresa.

---

## Stack

- **PHP 8.3** · **Laravel 13** · **PHPUnit 12**
- Banco: **MySQL 8** (produção) / **SQLite `:memory:`** (testes)
- Front-end: **Tailwind CSS** (CDN), **Alpine.js** (CDN), Blade
- Filas: **database** (`ShouldQueue`)
- Sem build step (assets via CDN) — `php artisan serve` basta.

## Requisitos

- PHP >= 8.3 (extensões: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- Composer 2
- MySQL 8 (ou MariaDB 10.4+)

## Instalação

```bash
composer install
cp .env.example .env        # ou use o .env existente
php artisan key:generate
# ajuste DB_DATABASE / DB_USERNAME / DB_PASSWORD no .env
php artisan migrate --seed
php artisan serve
```

Acesse `http://localhost:8000`. O primeiro cadastro cria a empresa (workspace) e o usuário proprietário com capacidade `*`.

## Multi-tenancy

Cada modelo de domínio usa o trait `App\Core\Concerns\BelongsToCompany` e herda `company_id`.
O contexto ativo é resolvido por `App\Core\Support\CompanyContext` (definido a partir do `CompanyUser` do usuário logado). Consultas sensíveis devem filtrar por `company_id` — o trait já injeta o escopo global `ForCurrentCompany` automaticamente.

## Módulos

| Módulo | Domínio | Status |
|---|---|---|
| CRM / Leads | `Crm` | ✅ |
| Clientes | `Cliente` | ✅ |
| Projetos & Tarefas | `Projeto` | ✅ |
| Agenda | `Agenda` | ✅ |
| Financeiro | `Financeiro` | ✅ |
| Produção | `Producao` | ✅ |
| Wiki | `Wiki` | ✅ |
| Equipamentos | `Equipamento` | ✅ |
| Lançamento de Horas | `Hora` | ✅ |
| Comercial / Contratos | `Comercial` | ✅ |
| Templates de Projeto | `Projeto` (Template) | ✅ |
| Campos Personalizados | `Core` | ✅ |
| RBAC + Config | `Company`/`Core` | ✅ |
| Dashboard Personalizado | `Core` | ✅ |
| Portal do Cliente | `Cliente` (Portal) | ✅ |
| Webhooks | `Core` | ✅ |
| Workflow / Automação | `Core` | ✅ (ações; estados via catálogo) |
| Relatórios | `Relatorios` | ✅ (CSV/Excel/PDF) |
| Notificações | `Core` | ✅ |
| Auditoria | `Core` | ✅ |
| Busca Global | `Core` | ✅ |
| Tags / Favoritos | `Core` | ✅ |
| Anexos Genéricos | `Core` | ✅ |
| Comentários (menções/reações) | `Core` | ✅ |
| Dark Mode | `layouts` | ✅ |
| Recuperação de Senha | `Auth` | ✅ |
| Verificação de E-mail | `Auth` | ✅ |
| Filas p/ Export | `Jobs` | ✅ (database) |

### Em implementação / roadmap (ver `docs/RELATORIO_IMPLEMENTACAO.md`)

- Workflows com **status configuráveis por entidade via UI** (hoje os estados vêm de `EntityCatalog`).
- **Checklists em templates** de projeto.
- **Menus configuráveis** por workspace.
- **Templates de workspace por segmento** (além do `agency`).
- Engines de **import/export** (NFe/XML) e integrações fiscais.
- API pública, app mobile, marketplace de plugins e sugestões de IA (roadmap).

## Testes

```bash
vendor/bin/phpunit --no-coverage
```

38 testes / 106 asserções (suíte verde). Detalhes em `docs/COMO_TESTAR.md`.

## Documentação

- `docs/RELATORIO_IMPLEMENTACAO.md` — o que foi implementado, decisões e gaps.
- `docs/COMO_IMPLEMENTAR.md` — arquitetura e como adicionar módulos/campos/automações.
- `docs/COMO_TESTAR.md` — como rodar e estender os testes.
- `docs/USO_E_PROCESSOS.md` — guia de uso e processos de operação.

## Licença

Uso interno.
