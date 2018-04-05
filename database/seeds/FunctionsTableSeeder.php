<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FunctionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('functions')->insert([
            [
                'module_id' => 1,
                'name' => 'Просмотр страницы ролей',
                'slug' => 'index',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 1,
                'name' => 'Просмотр страницы роли',
                'slug' => 'show',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 1,
                'name' => 'Создание ролей',
                'slug' => 'create',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 1,
                'name' => 'Редактирование ролей',
                'slug' => 'edit',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 1,
                'name' => 'Удаление ролей',
                'slug' => 'delete',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'module_id' => 2,
                'name' => 'Просмотр страницы пользователей',
                'slug' => 'index',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 2,
                'name' => 'Просмотр страницы пользователя',
                'slug' => 'show',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 2,
                'name' => 'Создание пользователей',
                'slug' => 'create',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 2,
                'name' => 'Редактирование пользователей',
                'slug' => 'edit',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 2,
                'name' => 'Удаление пользователей',
                'slug' => 'delete',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'module_id' => 3,
                'name' => 'Просмотр страницы категорий',
                'slug' => 'index',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 3,
                'name' => 'Просмотр страницы категории',
                'slug' => 'show',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 3,
                'name' => 'Создание категории',
                'slug' => 'create',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 3,
                'name' => 'Редактирование категории',
                'slug' => 'edit',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 3,
                'name' => 'Удаление категории',
                'slug' => 'delete',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'module_id' => 4,
                'name' => 'Просмотр страницы товаров',
                'slug' => 'index',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 4,
                'name' => 'Просмотр страницы товара',
                'slug' => 'show',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 4,
                'name' => 'Создание товара',
                'slug' => 'create',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 4,
                'name' => 'Редактирование товара',
                'slug' => 'edit',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 4,
                'name' => 'Удаление товара',
                'slug' => 'delete',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'module_id' => 5,
                'name' => 'Просмотр страницы фильтров',
                'slug' => 'index',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 5,
                'name' => 'Просмотр страницы фильтра',
                'slug' => 'show',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 5,
                'name' => 'Создание фильтра',
                'slug' => 'create',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 5,
                'name' => 'Редактирование фильтра',
                'slug' => 'edit',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 5,
                'name' => 'Удаление фильтра',
                'slug' => 'delete',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],

            [
                'module_id' => 6,
                'name' => 'Создание параметра фильтра',
                'slug' => 'create',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 6,
                'name' => 'Редактирование параметра фильтра',
                'slug' => 'edit',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'module_id' => 6,
                'name' => 'Удаление параметра фильтра',
                'slug' => 'delete',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
        ]);
    }
}
