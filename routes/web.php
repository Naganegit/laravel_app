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

/**
 * LaravelにはグローバルヘルパPHP関数と呼ばれるFramework固有の関数がある。
 * 参照：公式Docs（https://readouble.com/laravel/5.7/ja/helpers.html）
 * 
 * ルーティングを定義するweb.php（当ファイル）で使われているview()もヘルパ関数の一つ。
 * view(resources/viewsディレクトリ以下からのファイルパス[,ビューで使用するデータの配列])
 * 
 * また、コントローラー作成時に--resourceオプションをつけると、リソースコントローラが作られる。
 * リソースコントローラにはRestfulなアクションが定義されていて、全部で7種類。
 * index, create, show, edit, store, update, destroy
 * ルーティングはRoute::resource('ルート名', 'コントローラ名');とすればOK
 * 
 */

Route::get('/', function () {
    return view('welcome');
});

Route::resource('todo', 'TodoController');

Route::get('hello', function(){
    return 'Hello, World!';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
