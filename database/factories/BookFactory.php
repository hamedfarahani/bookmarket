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
        $authors = $this->faker->randomElements([$this->faker->name, $this->faker->name], 2);
        static $i = 0;
        return [
            'id'        => $i++,
            'publisher' => $this->faker->company(),
            'authors'   => is_array($authors) ? implode(',', $authors) : $authors,
            'title'     => $this->faker->sentence(2),
            'summary'   => $this->faker->sentence(50)
        ];
    }

}
