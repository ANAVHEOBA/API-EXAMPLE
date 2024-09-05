<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
class PostApiTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_can_fetch_all_posts()
    {
        Post::factory()->count(5)->create();
        $response = $this->getJson('/api/posts');
        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }
    /** @test */
    public function it_can_fetch_a_single_post()
    {
        $post = Post::factory()->create();
        $response = $this->getJson("/api/posts/{$post->id}");
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $post->id,
                     'title' => $post->title,
                     'body' => $post->body,
                 ]);
    }
    /** @test */
    public function it_can_create_a_post()
    {
        $postData = [
            'title' => 'New Post',
            'body' => 'This is the body of the new post.',
        ];
        $response = $this->postJson('/api/posts', $postData);
        $response->assertStatus(201)
                 ->assertJson($postData);
        $this->assertDatabaseHas('posts', $postData);
    }
    /** @test */
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create();
        $updatedData = [
            'title' => 'Updated Post Title',
            'body' => 'Updated post body content.',
        ];
        $response = $this->putJson("/api/posts/{$post->id}", $updatedData);
        $response->assertStatus(200)
                 ->assertJson($updatedData);
        $this->assertDatabaseHas('posts', $updatedData);
    }
    /** @test */
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create();
        $response = $this->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}