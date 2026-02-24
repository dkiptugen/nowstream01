<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;
use App\Models\Content;

class WatchHistoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create minimal users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        // Create minimal contents table (uuid primary key)
        Schema::create('contents', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('content_group')->nullable();
            $table->timestamps();
        });

        // Create minimal watch_histories table
        Schema::create('watch_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->uuid('content_id')->index();
            $table->timestamp('watched_at')->useCurrent();
            $table->integer('watch_duration')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('watch_histories');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_watch_history_recorded_when_authenticated_user_visits_video_show()
    {
        // Create a user directly (no factory dependency)
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test+' . Str::random(5) . '@example.com',
        ]);

        // Create content directly
        $content = Content::create([
            'uuid' => (string) Str::uuid(),
            'title' => 'Test Video ' . Str::random(5),
            'slug' => 'test-video-' . Str::random(5),
            'content_group' => 'video',
        ]);

        $response = $this->actingAs($user)
            ->get('/video/' . $content->uuid);

        $response->assertStatus(200);

        $this->assertDatabaseHas('watch_histories', [
            'user_id' => $user->id,
            'content_id' => $content->uuid,
        ]);
    }
}
