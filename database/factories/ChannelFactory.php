<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Channel>
 */
class ChannelFactory extends Factory
{
    protected $model = Channel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identifier' => substr($this->faker->shuffleString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8),
            'name' => $this->faker->sentence(3),
            'thumbnail' => $this->generateUnsplashImageUrl(100, 100, $this->faker->domainWord),
            'cover_image' => $this->generateUnsplashImageUrl(1024, 300, $this->faker->domainWord),
            'description' => collect($this->faker->paragraphs(3))
                ->map(fn($paragraph) => "<p>$paragraph</p>")
                ->implode(''),
            'status' => 1,
            'stream_partner_id' => $this->faker->numberBetween(1, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    private function generateUnsplashImageUrl($width, $height, $category)
    {
        // return "https://source.unsplash.com/random/{$width}x{$height}/?{$category}";

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
