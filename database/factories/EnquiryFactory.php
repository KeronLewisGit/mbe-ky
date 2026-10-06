<?php

namespace Database\Factories;

use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => 'contact',
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'summary' => 'General enquiry',
            'data' => ['topic' => 'General enquiry', 'message' => fake()->sentence()],
            'status' => 'new',
        ];
    }
}
