<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stream>
 */
class StreamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
		$streamkey = Str::random(8);
        return [
			'identifier'=>  Str::random(8),
			'title'=> $this->faker->sentence,
			'description'=>collect($this->faker->paragraphs(3))
                ->map(fn($paragraph) => "<p>$paragraph</p>")
                ->implode(''),
			'thumbnail_url'=>$this->generateUnsplashImageUrl (800,500,$this->faker->domainWord),
			'stream_key'=>$streamkey,
			'stream_url'=>'rtmp://stream.livestreamz.xyz/live',
			'stream_video_link' => 'https://stream.livestreamz.xyz/hls/'.$streamkey.'.m3u8',
			'start_time' => Carbon::now(),
			'end_time' => Carbon::now()->addHours(rand(1,8)),
			'status' => rand(0,1),
			'event_id'=>$this->faker->numberBetween (1,1500),
			'system_user_id'=>1,
			'channel_id'=>$this->faker->numberBetween (1,1500),

            //
        ];
    }
		private function generateUnsplashImageUrl($width, $height, $category)
			{
				 // Validate the width and height to be positive integers
        if (!is_int($width) || !is_int($height) || $width <= 0 || $height <= 0) {
            throw new InvalidArgumentException("Width and height must be positive integers.");
        }
    
        // Base URL for Picsum photos
        $url = "https://picsum.photos/{$width}/{$height}";
    
        // Add a random query parameter to ensure a random image
        $url .= '?random=' . rand(1, 1000);
    
        // Return the constructed URL
        return $url;
			}

}
