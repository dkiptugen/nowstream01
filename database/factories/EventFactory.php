<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_name' => $this->faker->sentence,
            'description'=>collect($this->faker->paragraphs(3))
                ->map(fn($paragraph) => "<p>$paragraph</p>")
                ->implode(''),
            'event_image' => $this->generateUnsplashImageUrl(500,800,$this->faker->domainWord),
            'publish_date' =>  $this->faker->date,
            'status' => 1,
            'system_user_id'=> 1,
            'channel_id'=> $this->faker->numberBetween(1,20)
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
