<?php

namespace Database\Factories;

use App\Models\StreamPartner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StreamPartner>
 */
class StreamPartnerFactory extends Factory
{
    protected $model = StreamPartner::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name,
            'reg_no'=>substr($this->faker->shuffleString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8),
            'address'=>$this->faker->address(),
            'registration_date' => $this->faker->dateTime(),
            'pin_no'=>$this->faker->word(),
            'legal_documents' =>['pin_cert'=>''],
            'system_user_id'=>$this->faker->numberBetween(1,20),

        ];
    }
}
