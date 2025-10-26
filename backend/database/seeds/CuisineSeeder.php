<?php
namespace Database\Seeders;
use App\Models\Cuisine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CuisineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $cuisines = [
            ['name' => 'Italian', 'description' => 'Pizza, Pasta, and more'],
            ['name' => 'Chinese', 'description' => 'Traditional Chinese dishes'],
            ['name' => 'Mexican', 'description' => 'Tacos, Burritos, and more'],
            ['name' => 'Indian', 'description' => 'Curry, Biryani, and more'],
            ['name' => 'Japanese', 'description' => 'Sushi, Ramen, and more'],
            ['name' => 'American', 'description' => 'Burgers, BBQ, and more'],
            ['name' => 'Thai', 'description' => 'Pad Thai, Curries, and more'],
            ['name' => 'Mediterranean', 'description' => 'Greek, Lebanese, and more'],
        ];

        foreach ($cuisines as $cuisine) {
            Cuisine::create([
                'name' => $cuisine['name'],
                'slug' => Str::slug($cuisine['name']),
                'description' => $cuisine['description'],
                'is_active' => true,
            ]);
        }
    }
}