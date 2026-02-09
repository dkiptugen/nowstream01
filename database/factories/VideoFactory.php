<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VideoFactory extends Factory
{
	protected $model= Content::where('type', 'video')->class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'channel_id'=>$this->faker->numberBetween (1,1500),
        'event_id'=>$this->faker->numberBetween (1,1500),
        'title'=>$this->faker->sentence,
        'description'=>collect($this->faker->paragraphs(3))
            ->map(fn($paragraph) => "<p>$paragraph</p>")
            ->implode(''),
        'thumbnail' => $this->generateUnsplashImageUrl(800,500,$this->faker->domainWord),
        'video_path' => $this->generateYouTubeUrl(),
        'system_user_id' =>1


        ];
    }
		private function generateUnsplashImageUrl($width, $height, $category)
			{
				return "https://source.unsplash.com/random/{$width}x{$height}/?{$category}";
			}
		private function generateYouTubeUrl()
			{
				$videoId = $this->generateYouTubeVideoId();
				return "https://www.youtube.com/watch?v={$videoId}";
			}

		private function generateYouTubeVideoId()
			{
				// Generate an 8-character long string
				return substr($this->faker->shuffleString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_'), 0, 8);
			}
}
