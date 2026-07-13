<?php

namespace App\Domains\Wiki\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Artigo extends Model
{
    use BelongsToCompany;
    use HasActivity;
    use HasUlid;
    use Searchable;
    use SoftDeletes;

    protected $table = 'wiki_artigos';

    protected $searchable = ['title', 'body'];

    protected $fillable = [
        'company_id', 'title', 'slug', 'body', 'category', 'status', 'author_id', 'client_visible',
    ];

    protected $casts = [
        'client_visible' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title).'-'.substr((string) Str::ulid(), -6);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
