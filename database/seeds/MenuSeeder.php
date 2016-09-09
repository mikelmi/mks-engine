<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $main = Menu::where('name', 'Main')->first();
        if (!$main) {
            $main = Menu::create(['name' => 'Main']);
        }

        $left = Menu::where('name', 'Left')->first();
        if (!$left) {
            $left = Menu::create(['name' => 'Left']);
        }

        MenuItem::truncate();
            MenuItem::create([
                'title' => 'Home',
                'route' => 'home',
                'menu_id' => $main->id
            ]);
            MenuItem::create([
                'title' => 'Contacts',
                'url' => '/contacts',
                'menu_id' => $main->id
            ]);
            MenuItem::create([
                'title' => 'Projects',
                'menu_id' => $main->id,
                'children' => [
                    ['title' => 'Mks', 'menu_id' => $main->id],
                    ['title' => 'Web', 'menu_id' => $main->id,
                        'children' => [
                            ['title' => 'CMS', 'menu_id' => $main->id],
                            ['title' => 'AdminPanel', 'menu_id' => $main->id],
                        ]
                    ],
                    ['title' => 'Teacher Reports', 'menu_id' => $main->id],
                ]
            ]);
            MenuItem::create([
                'title' => 'About',
                'url' => '/about',
                'menu_id' => $main->id
            ]);

        MenuItem::create([
            'title' => 'Settings',
            'route' => 'set',
            'menu_id' => $left->id
        ]);
        MenuItem::create([
            'title' => 'Blog',
            'url' => '/blog',
            'menu_id' => $left->id
        ]);
        MenuItem::create([
            'title' => 'News',
            'menu_id' => $left->id,
            'children' => [
                ['title' => 'Ukraine'],
                ['title' => 'World'],
                ['title' => 'Sport'],
            ]
        ]);
        MenuItem::create([
            'title' => 'List',
            'url' => '/about',
            'menu_id' => $left->id
        ]);
    }
}
