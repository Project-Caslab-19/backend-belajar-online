<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = ['Rekayasa Perangkat Lunak', 'Game Cerdas', 'Data Science', 'Keamanan Jaringan', 'Machine Learning'];
        $description = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel odio non justo aliquet auctor. Sed vitae maximus magna. Phasellus nec suscipit ligula. Duis sollicitudin eu risus non bibendum. In accumsan massa eu ipsum sollicitudin, a condimentum arcu suscipit. Vivamus accumsan sagittis metus nec varius. Aliquam erat volutpat. Sed laoreet pharetra ornare. Nunc mi odio, convallis vitae porttitor sed, egestas sed odio. Nullam malesuada sagittis odio.
        Vestibulum aliquet nisi at sodales placerat. Cras congue, nisl vel dignissim accumsan, diam risus vestibulum sem, tempor faucibus mi lorem nec turpis. Fusce malesuada sodales molestie. Proin dui arcu, fringilla non mi at, tempus scelerisque tortor. Curabitur vel pretium mi, quis tempus metus. In feugiat, nulla at vehicula consequat, nisi dui varius lacus, nec scelerisque purus nulla et tellus. Nam a viverra sapien.
        Praesent ac ante efficitur, ullamcorper arcu sed, condimentum sem. In vel imperdiet metus. Fusce vel placerat nunc. Donec dui felis, pellentesque at purus et, venenatis condimentum ex. Integer bibendum mi eu mi rhoncus interdum. Curabitur vel mattis ante. Vestibulum ipsum est, euismod vel arcu ultricies, tincidunt posuere felis. Nullam molestie eget arcu sed porttitor. In vel porta felis. Aenean turpis dolor, viverra ut venenatis sed, commodo a urna. Donec auctor urna ut elementum gravida. Integer vel malesuada diam. Duis odio dui, vestibulum sit amet ipsum quis, eleifend accumsan nisi. Vivamus pretium risus dolor, eu tempor lectus dignissim nec. Nam aliquet neque at malesuada condimentum. Nullam venenatis vehicula magna, eu malesuada leo euismod vitae.';

        foreach($category as $c)
        {
            $insert = [
                'name' => $c,
                'description' => $description,
            ];

            $model = Category::create($insert);
        }
    }
}
