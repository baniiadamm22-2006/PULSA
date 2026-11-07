<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Pulsa Reguler',
            'Paket Data Internet',
            'Roaming Internasional',
            'Roaming Umroh',
            'Token Listrik PLN',
        ];

        foreach ($categories as $index => $name) {
            Category::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => null, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}
