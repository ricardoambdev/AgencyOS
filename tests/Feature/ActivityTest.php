<?php

namespace Tests\Feature;

use App\Core\Models\Attachment;
use App\Core\Models\Favorite;
use App\Core\Models\Tag;
use App\Domains\Crm\Models\Lead;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_favorite_and_unfavorite_a_lead(): void
    {
        $user = $this->ownerUser();
        $lead = Lead::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => 'Lead Fav',
        ]);

        $this->actingAs($user)
            ->post(route('favoritos.toggle'), [
                'entity_type' => get_class($lead),
                'entity_id' => $lead->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'entity_type' => get_class($lead),
            'entity_id' => $lead->id,
        ]);

        $this->actingAs($user)
            ->post(route('favoritos.toggle'), [
                'entity_type' => get_class($lead),
                'entity_id' => $lead->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('favorites', 0);
    }

    public function test_lead_create_persists_tags(): void
    {
        $user = $this->ownerUser();

        $this->actingAs($user)
            ->post('/leads', [
                'name' => 'Lead Tag',
                'tags' => 'urgente, vip, reforma',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tags', ['slug' => 'urgente']);
        $this->assertDatabaseHas('tags', ['slug' => 'vip']);
        $this->assertEquals(3, Tag::count());
    }

    public function test_owner_can_upload_attachment_to_lead(): void
    {
        Storage::fake('local');
        $user = $this->ownerUser();
        $lead = Lead::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => 'Lead Anexo',
        ]);

        $this->actingAs($user)
            ->post(route('attachments.store'), [
                'entity_type' => get_class($lead),
                'entity_id' => $lead->id,
                'file' => UploadedFile::fake()->create('doc.pdf', 12),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('attachments', [
            'entity_type' => get_class($lead),
            'entity_id' => $lead->id,
            'name' => 'doc.pdf',
        ]);
    }

    public function test_can_react_to_comment(): void
    {
        $user = $this->ownerUser();
        $lead = \App\Domains\Crm\Models\Lead::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => 'Lead Reação',
        ]);
        $comment = \App\Core\Models\Comment::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'entity_type' => get_class($lead),
            'entity_id' => $lead->getKey(),
            'user_id' => $user->id,
            'body' => 'Comentário de teste',
            'visibility' => 'internal',
        ]);

        $this->actingAs($user)
            ->postJson(route('comments.react', $comment), ['emoji' => '👍'])
            ->assertOk()
            ->assertJsonFragment(['emoji' => '👍', 'count' => 1, 'reacted' => true]);

        $this->assertTrue(in_array($user->id, $comment->fresh()->reactions['👍'] ?? [], true));

        $this->actingAs($user)
            ->postJson(route('comments.react', $comment), ['emoji' => '👍'])
            ->assertOk()
            ->assertJsonFragment(['emoji' => '👍', 'count' => 0, 'reacted' => false]);
    }

    public function test_comment_parses_mentions(): void
    {
        $user = $this->ownerUser();
        $lead = \App\Domains\Crm\Models\Lead::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => 'Lead Menção',
        ]);

        $this->actingAs($user)
            ->post(route('comments.store'), [
                'entity_type' => get_class($lead),
                'entity_id' => $lead->ulid,
                'body' => 'Olá @Ana e @Bruno, vejam isso',
                'visibility' => 'internal',
                'redirect' => '/',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', ['body' => 'Olá @Ana e @Bruno, vejam isso']);
        $comment = \App\Core\Models\Comment::where('body', 'Olá @Ana e @Bruno, vejam isso')->first();
        $this->assertEquals(['@Ana', '@Bruno'], $comment->mentions);
    }
}
