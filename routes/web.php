<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Admin
Route::middleware(['admin'])->prefix('admin')->namespace('Admin')->group(function () {

    Route::get("/", "AdminController@index");

    Route::middleware(['access'])->group(function () {

        //Users
        Route::prefix('user')->group(function () {
            Route::get('/', 'UserController@index')->name('user.index');
            Route::get('/show/{id}', 'UserController@show')->name('user.show');
            Route::get('/create', 'UserController@create')->name('user.create');
            Route::post('/create', 'UserController@store')->name('user.store');
            Route::post('/edit', 'UserController@edit')->name('user.edit');
            Route::post('/delete', 'UserController@delete')->name('user.delete');
        });

        //Roles
        Route::prefix('role')->group(function () {
            Route::get('/', 'RoleController@index')->name('role.index');
            Route::get('/show/{id}', 'RoleController@show')->name('role.show');
            Route::get('/create', 'RoleController@create')->name('role.create');
            Route::post('/create', 'RoleController@store')->name('role.store');
            Route::post('/edit', 'RoleController@edit')->name('role.edit');
            Route::post('/delete', 'RoleController@delete')->name('role.delete');
        });

        //Categories
        Route::prefix('category')->group(function () {
            Route::get('/', 'CategoryController@index')->name('category.index');
            Route::get('/show/{id}', 'CategoryController@show')->name('category.show');
            Route::get('/create', 'CategoryController@create')->name('category.create');
            Route::post('/create', 'CategoryController@store')->name('category.store');
            Route::post('/edit', 'CategoryController@edit')->name('category.edit');
            Route::post('/delete', 'CategoryController@delete')->name('category.delete');

            Route::post('/rebuild-edit', 'CategoryController@rebuildTree');
            Route::post('/check-delete', 'CategoryController@checkDelete');
        });

        //Goods
        Route::prefix('good')->group(function () {
            Route::get('/', 'GoodController@index')->name('good.index');
            Route::get('/category-index/{id}', 'GoodController@category')->name('good.category');
            Route::get('/show/{id}/{cat?}', 'GoodController@show')->name('good.show');
            Route::get('/create/{cat?}', 'GoodController@create')->name('good.create');
            Route::post('/create/{cat?}', 'GoodController@store')->name('good.store');
            Route::post('/edit/{cat?}', 'GoodController@edit')->name('good.edit');
            Route::post('/delete/{cat?}', 'GoodController@delete')->name('good.delete');

            Route::post('/create_edit-filter', 'GoodController@getCategoryFilters');
        });

        //Filters
        Route::prefix('filter')->group(function () {
            Route::get('/', 'FilterController@index')->name('filter.index');
            Route::get('/category-index/{id}', 'FilterController@category')->name('filter.category');
            Route::get('/show/{id}/{cat?}', 'FilterController@show')->name('filter.show');
            Route::get('/create/{cat?}', 'FilterController@create')->name('filter.create');
            Route::post('/create/{cat?}', 'FilterController@store')->name('filter.store');
            Route::post('/edit/{cat?}', 'FilterController@edit')->name('filter.edit');
            Route::post('/delete/{cat?}', 'FilterController@delete')->name('filter.delete');

            Route::post('/category-update', 'FilterController@categoryUpdate');
        });

    });
});

//User
Route::middleware(['user'])->prefix('user')->namespace('User')->group(function () {

});

Route::namespace('Site')->group(function () {
    Route::get('/', 'HomeController@index')->name('site.index');


    Route::get('/category/{slug}', 'CategoryController@show')->name('site.category.show');

    Route::get('/good/{slug}', 'GoodController@show')->name('site.good.show');
});