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

//Produtos
Route::get('/produtos', ['uses' => 'ProductController@index', 'as' => 'product.index']);
Route::get('/produtos/add', ['uses' => 'ProductController@add', 'as' => 'product.add']);
Route::post('/produtos/save', ['uses' => 'ProductController@save', 'as' => 'product.save']);
Route::get('/produtos/edit/{id}', ['uses' => 'ProductController@edit', 'as' => 'product.edit']);
Route::post('/produtos/update/{id}', ['uses' => 'ProductController@update', 'as' => 'product.update']);
Route::get('/produtos/delete/{id}', ['uses' => 'ProductController@delete', 'as' => 'product.delete']);
Route::put('/produtos/search', ['uses' => 'ProductController@search', 'as' => 'product.search']);

//Categorias
Route::get('/categorias', ['uses' => 'CategoryController@index', 'as' => 'category.index']);
Route::get('/categorias/add', ['uses' => 'CategoryController@add', 'as' => 'category.add']);
Route::post('/categorias/save', ['uses' => 'CategoryController@save', 'as' => 'category.save']);
Route::get('/categorias/edit/{id}', ['uses' => 'CategoryController@edit', 'as' => 'category.edit']);
Route::post('/categorias/update/{id}', ['uses' => 'CategoryController@update', 'as' => 'category.update']);
Route::get('/categorias/delete/{id}', ['uses' => 'CategoryController@delete', 'as' => 'category.delete']);
Route::put('/categorias/search', ['uses' => 'CategoryController@search', 'as' => 'category.search']);