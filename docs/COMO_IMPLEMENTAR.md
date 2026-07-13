# Como Implementar — AgencyOS

Guia para estender a plataforma. Pressupõe familiaridade com Laravel e a estrutura DDD deste projeto.

---

## 1. Convenções obrigatórias

Todo modelo de domínio **deve**:

```php
namespace App\Domains\MeuDominio\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;

class Foo extends Model
{
    use BelongsToCompany;   // company_id + escopo global ForCurrentCompany
    use HasUlid;            // chave pública 'ulid' (usada em URLs/relacionamentos morph)

    protected $fillable = [/* ... */];
}
```

- **Nunca** exponha o `id` (bigint) em URLs. Use `ulid`.
- Filtre sempre por `company_id` (o trait já faz isso via escopo global).
- Crie uma **Policy** em `app/Policies/` (pode retornar `true`; o RBAC real é por capacidade).

---

## 2. Adicionar um novo domínio/módulo

1. Crie `app/Domains/MeuDominio/Models/Foo.php` (com `BelongsToCompany` + `HasUlid`).
2. Crie a migration (`php artisan make:migration create_foos_table`). Inclua `company_id`, `ulid`, `user_id` (owner) quando aplicável.
3. Crie `app/Domains/MeuDominio/Controllers/FooController.php` estendendo `App\Http\Controllers\Controller`.
4. Crie as views em `resources/views/meudominio/`.
5. Registre as rotas em `routes/web.php` dentro do grupo `auth` (ou `auth` + `can:cap:xxx`).
6. (Opcional) Adicione capacidades ao `Role` em `config()` ou seeder.

### Rotas — padrão
```php
Route::middleware('auth')->group(function () {
    Route::get('foos', [FooController::class, 'index'])->name('foos.index')->can('cap:foo.view');
    Route::get('foos/create', [FooController::class, 'create'])->name('foos.create')->can('cap:foo.create');
    Route::post('foos', [FooController::class, 'store'])->name('foos.store')->can('cap:foo.create');
    Route::get('foos/{foo}', [FooController::class, 'show'])->name('foos.show')->can('cap:foo.view');
    // ...
});
```

---

## 3. Tags, Favoritos e Anexos (cross-domain)

Já prontos em `Core`. Para habilitar em uma entidade:

```php
// Modelo
use App\Core\Concerns\HasActivity;
class Foo extends Model { use HasActivity; }
```

```blade
{{-- Na view show --}}
@include('partials.entity-activity', ['model' => $foo])
```

```blade
{{-- No formulário --}}
@include('partials.tags-input', ['model' => $foo])
```

- `HasActivity` provê: `tags()`, `favorites()`, `attachments()`, `comments()`, `syncTags(iterable $names)`.
- O partial `entity-activity` renderiza favoritar + tags + anexos. Comentários usam `@include('partials.comments', ['model' => $foo])`.

---

## 4. Campos Personalizados (EAV)

```blade
@include('partials.custom-fields-form', ['model' => $foo])
@include('partials.custom-fields-show', ['model' => $foo])
```
O `CustomFieldEngine` persiste em `custom_values`. Tipos: `text|number|select|date|boolean|file`.

---

## 5. Automações / Workflow

Criar uma `Automation` (tela `config/automations`):

- **Gatilho:** `entity.created | entity.updated | comment.added | task.done | invoice.paid`
- **Condição (opcional):** campo/operador/valor
- **Ação:** `notify | webhook | email | change_status | assign`

O `WorkflowEngine` é invocado pelos observers/eventos dos modelos e executa a ação. Para novo gatilho: disparar o evento no modelo e registrá-lo no `WorkflowEngine::handle()`.

### Webhooks
`WebhookEngine::dispatch($event, $payload)` envia `POST` JSON com header `X-AgencyOS-Signature` (HMAC do body com o `secret` do webhook).

---

## 6. Relatórios

`ReportEngine::export(string $report): array` retorna linhas (arrays). Para novo relatório:

1. Adicione o caso em `ReportEngine::export()` e nos métricos (`kpis`, etc.).
2. Adicione o título em `ReportController::export()` (array `$titles`).
3. Valide o nome em `abort_unless(in_array($report, [...]))` no controller e no job `BuildReportExport`.

Formatos suportados: `csv` (padrão), `xlsx` (Spreadsheet XML), `pdf` (print view `relatorios/print.blade.php`).

### Export em fila (database)
`ReportController::export()` já dispara `BuildReportExport::dispatch(...)`. Para produção:
```env
QUEUE_CONNECTION=database
```
```bash
php artisan queue:work --queue=exports
```

---

## 7. Comentários com menções e reações

- `CommentController::store` já faz `preg_match_all('/@([\p{L}\p{N}_.]+)/u', ...)` e salva em `mentions` (JSON).
- Reações: `POST /comments/{comment}/react` (JSON) com `{ "emoji": "👍" }`. O botão no partial `comments.blade.php` usa Alpine + `fetch` e lê o `csrf-token` do `<meta>`.
- Para adicionar um emoji: inclua no array `['👍','❤️','🎉']` no partial e no `CommentController::toggleReaction`.

---

## 8. Autenticação e verificação

- `AuthController` concentra login/registro/recuperação/verificação.
- Novo usuário: `User` implementa `MustVerifyEmail`; o link é assinado (`verification.verify`).
- `config/auth.php` usa `App\Domains\Usuario\Models\User`.
- **Não** crie migration para `password_reset_tokens` — já existe na migration default de `users`.

---

## 9. Testes

Ver `docs/COMO_TESTAR.md`. Padrão de teste de feature:

```php
$user = $this->ownerUser();           // cria empresa + usuário owner (CompanyContext)
$foo  = Foo::create([/* company_id via CompanyContext */]);
$this->actingAs($user)->get(route('foos.index'))->assertOk();
```
