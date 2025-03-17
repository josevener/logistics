<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10), // Random user_id (adjust as needed)
            'fname' => $this->faker->firstName(),
            'mname' => $this->faker->optional()->firstName(),  // Optional middle name
            'lname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'contact_info' => $this->faker->phoneNumber(),
        ];
    }

    /**
     * Define the vendor's name by concatenating fname, mname, and lname.
     *
     * @param array $attributes
     * @return array
     */
    public function configure()
    {
        return $this->afterCreating(function (Vendor $vendor) {
            $vendor->name = $vendor->fname . ' ' . ($vendor->mname ? "{$vendor->mname} " : '') . $vendor->lname;
            $vendor->save();
        });
    }
}
