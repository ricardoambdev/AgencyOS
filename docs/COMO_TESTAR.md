# Como Testar — AgencyOS

Suíte automatizada em **PHPUnit 12**. Banco de testes: **SQLite `:memory:`** com `RefreshDatabase`.

---

## 1. Pré-requisitos

- PHP >= 8.3, Composer 2.
- Dependências instaladas: `composer install`.
- `.env` presente (use `.env.example` como base). Testes usam `phpunit.xml` que sobrescreve conexões:
  - `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`
  - `QUEUE_CONNECTION=sync`, `CACHE_STORE=array`, `SESSION_DRIVER=array`, `MAIL_MAILER=array`

## 2. Rodar os testes

```bash
# todos
vendor/bin/phpunit --no-coverage

# um arquivo
vendor/bin/phpunit --no-coverage tests/Feature/AuthTest.php

# um método
vendor/bin/phpunit --no-coverage --filter test_login_succeeds_with_valid_credentials

# com cobertura (se xdebug habilitado)
vendor/bin/phpunit --coverage-html coverage
```

**Status atual:** 38 testes / 106 asserções — verde.

## 3. O que é coberto

| Arquivo | Cenários |
|---|---|
| `AuthTest` | guest redirecionado, login OK/falha, logout, **reset de senha**, **verificação de e-mail**, proteção de rota |
| `ActivityTest` | favoritar/desfavoritar, persistência de tags (`syncTags`), upload de anexo genérico, **reações em comentário**, **menções em comentário** |
| `SearchReportTest` | busca global, index de relatórios, export **CSV/Excel/PDF**, **job de export em fila grava arquivo** |
| `RBAC/dashboards/domínios | criação/listagem sob autenticação, permissões básicas |

## 4. Helpers de teste

- `TestCase` + `RefreshDatabase`.
- `$this->ownerUser()` (em `tests/`) cria empresa + usuário proprietário e define o `CompanyContext` — **use sempre** para criar registros de domínio (senão `company_id` fica nulo e a constraint falha).
- `Lead::create([...])` (Lead não possui factory; use `create`).
- `Comment::create([...])` com `entity_type`, `entity_id` (bigint), `user_id`, `body`, `visibility`.

## 5. Boas práticas para novos testes

1. Use `actingAs($this->ownerUser())` antes de chamadas autenticadas.
2. Mensagens de erro: `->assertSessionHasErrors(...)`; CSRF já é desabilitado em testes.
3. Para rotas assinadas (verificação de e-mail), gere a URL com:
   ```php
   URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
       'id' => $user->id, 'hash' => sha1($user->email),
   ]);
   ```
   > A rota `verification.verify` é **stateless** (fora do grupo `guest`), pois usuários já logados também a acessam.
4. Para filas: `Queue::fake()` + `assertPushed`; para rodar o job de fato, instancie e chame `->handle()` (ou `Bus::dispatchSync`).
5. Para uploads: `UploadedFile::fake()->create('doc.pdf', 12, 'application/pdf')` + `Storage::fake('local')`.

## 6. Verificação manual (smoke test)

```bash
php artisan serve
# 1. /register -> cria empresa + owner, envia e-mail de verificação
# 2. /email/verify/{id}/{hash} -> marca verificado
# 3. /favoritos -> lista favoritos
# 4. Abrir um Lead -> favoritar, adicionar tag, comentar com @menção e reagir
# 5. /relatorios -> exportar CSV/Excel/PDF
# 6. Toggle dark mode no topo
# 7. /forgot-password -> fluxo de reset
```
