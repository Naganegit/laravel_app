<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * LaravelにはEloquentモデルというORMを導入している。
 * 参照：Laravel公式Docs（https://readouble.com/laravel/5.7/ja/eloquent.html）
 * Eloquentモデルはクエリビルダであり、データの操作を容易にする。
 * また、メソッドを実行するときにPDOクラスのバインドを実行してくれるのでパラメータをエスケープする必要がない。
 * 特定のカラムに対してデータの操作を許可したい時、$fillableもしくは$guardedをModelに定義する。
 * $fillableはホワイトリストの役割を果たし、$guardedはブラックリストの役割を果たす。
 * これらの変数を同時に定義することはできない。
 * 
 * 
 */
class Todo extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'user_id',
    ];

    public function getByUserId($id)
    {
        return $this->where('user_id', $id)->get();
    }
}
