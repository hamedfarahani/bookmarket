<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition():array
    {
        static $i = 0;
        return [
            'id'        => $i++,
            'publisher' => $this->faker->company(),
            'authors'   => $this->faker->randomElement([$this->faker->name,$this->faker->name]),
            'title'     => $this->faker->sentence(2),
            'summary'   => $this->faker->sentence(50)
        ];
    }

}
