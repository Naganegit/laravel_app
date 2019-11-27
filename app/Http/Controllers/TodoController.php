<?php

/**
 * ・migrationファイルとseedファイルについて
 * migrationファイル：php言語でDBを操作するためのファイル。
 * artisanを使いmigrateすることで簡単にテーブルを定義しなおすことができる。
 * seedファイル：php言語で初期データを作成するファイル。
 * artisanを使いseedすることで簡単に初期データを投入することができる。
 * php artisan db:migrate --seedでmigrateとseedを同時に行えるようになる。
 * 
 * 複数人で開発する場合、DB構築や初期データの登録が行いやすくなるメリットがある。
 * 
 * 使用ライブラリ(config/app.php)
 * laravelCollective ...  Collective\Html\HtmlServiceProvider::class
 * HtmlBuilder ...  'Html' => Collective\Html\HtmlFacade::class
 * FormBuilderf ...  'Form' => Collective\Html\FormFacade::class
 * 
 * 
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Auth;

class TodoController extends Controller
{
    private $todo;

    /**
     * ・コンストラクタインジェクション
     * public function __construct(Todo $instanceClass)　←　ここ
     * インジェクション　…　注入　という意味。
     * コンストラクタが実行するときにTodoインスタンスを生成し、$instanceClassに格納している。
     * Laravelコントローラー特有のインスタンス生成方法　
     * 
     * ・コンストラクタインジェクションで生成したTodoインスタンスをメンバ変数のtodoにいれているのは何故か？
     * Todoクラスのメソッドを使いたいので,
     * Todoクラスのインスタンスを生成する必要がある。
     * 各メソッドでメソッドインジェクションをしてもよいが、同じコードを何回も書くのは二度手間。
     * TodoControllerがインスタンス化したときに自動的に作るよう、
     * コンストラクタインジェクションを利用して$instanceClassとして宣言した。
     * そのままではスコープの関係上、
     * コンストラクタ内でしか$instanceClassを利用できないため、
     * クラスのメンバ変数$tdoに格納した。
     * 
    */
    public function __construct(Todo $instanceClass)
    {
        $this->middleware('auth')->except('index');
        $this->todo = $instanceClass;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * ・compact()を利用する理由を答えよ。
     * compact関数は変数名をパラメータにとり、変数名をkey、変数の値をvalueとした連想配列を作成してくれる。
     * 今回はコレクションクラスのインスタンスを$todosに格納している。
     * bladeの方で$todosとしてコレクションクラスのインスタンスを操作したいので、compactを利用した。
     * ちなみに、　return view('todo.index',$todos);　←　これだとエラーが出る。
     * また、return view('todo.index',['$todos' => $todos]);　とcompactを利用したものは同じ挙動になる。
     * 
     * ・indexメソッド内のall()で返ってきているもの
     * Collectionインスタンス
     * オブジェクトを配列のように扱えるすごいやつ
     * Collectionクラスの中にObjectクラスのインスタンスが配列で入っている。
     * 
     * ・配列とオブジェクトの違い
     * 配列…オブジェクトよりも簡単にデータの管理を行える。メソッドの定義は行えない。
     * オブジェクト…メソッドの定義ができる。アクセス修飾子が使える。
     * ※ちなみに、連想配列に限ってはオブジェクトへの変換ができるらしい。
     * 
    */

    public function index()
    {
        $todos = $this->todo->getByUserId(Auth::id());
        return view('todo.index', compact('todos'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('todo.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * ・メソッドインジェクション
     * メソッドのパラメータでクラスのインスタンスを生成する。
     * public function store(Request $request)　←　ここ
     * storeメソッドではview側のルートで呼ばれた時にコントローラがインスタンス化して、コンストラクタが呼ばれる。
     * そのあとメソッドインジェクションでリクエストクラスのインスタンスが作られている。
     * リクエストクラスの中身はリクエストヘッダである。
     * allメソッドを実行することでリクエストに入力されたパラメータを連想配列の形で返している。
     * 今回は'_token'と'title'。
     * ここで_tokenを返しているのはクライアント側での書き換えを防止するため。（CSRF対策）
     * ちなみに、トークンの値は
     * \storage\framework\sessions　の中に格納されていて、
     * ここの値とクライアントのCokkieに格納されている値を比較している。
     * 
     * ・storage\framework以下のファイルに関する情報
     * コンパイルされたbladeファイル、session情報、log情報、cache情報等が格納されている。
     * 
     * 
     * ・EloquentクラスがEloquentが複数のレコードをリターンする場合は、
     * Illuminate\Database\Eloquent\Collectionのインスタンスを返される。
     * CollectionクラスはEloquentクラスのメソッドから返ってきたものを操作するメソッドを提供している。
     * Objectをforeachでまわすなどの、Objectを配列として操作できるような多くのクラスを提供している。
     * コレクションクラスのパス
     * 　 \vendor\laravel\framework\src\Illuminate\Support\Collection.php
     * （参照：https://readouble.com/laravel/5.7/ja/eloquent-collections.html#available-methods）
     * コレクションクラスはimplements演算子を使って、
     * ArrayAccessインターフェイスとIteratorAggregateインターフェイスを実装している。
     * これらのインターフェイスを実装することで、オブジェクトを配列として扱えるようになる。
     * 
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $this->todo->fill($input)->save();
        return redirect()->to('todo');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $todo = $this->todo->find($id);
        return view('todo.edit', compact('todo'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * ここでPOSTされたものは、クライアントから送信されたデータ
     * 
     * ・find()
     * Eloquentクラスのfindメソッドは、主キーに対して検索をかけるメソッド。
     * 検索し、該当したオブジェクトを返す。
     * 
     * ・fill()
     * fillメソッドの実行前はオブジェクトのattributeには何も値が格納していない。（ddメソッドでデバッグ可）
     * fillメソッドを実行すると、
     * ホワイトリスト（Modelの$fillable）/ブラックリスト（Modelの$guarded）で更新してよいカラムの値が変更され、オブジェクト内のattributeに配列として格納される。
     * 今回はModel側にfillableを一つだけ指定したためfillメソッドの実行時にカラム名を書かなかったが、fillableに複数設定したときはカラム名と値の両方書く必要がある。
     * 
     * ・save()
     * カラム名が一致しているもの同士のDBにある値と、実行したインスタンスのattributesを比べて、値が変更されたものに対してSQL文を実行する。
     * 値が変更されたものだけなので、例えば更新処理が走っても値が変更されていない場合は、更新されずupdate_atも更新されない。
     * 
     * ・redirect()->to('todo');
     * redirectメソッドを引数を渡さないで実行すると、Redirectorインスタンスを生成する。
     * そして、Redirectorクラスのメソッドであるtoメソッドにtodoまでのパスを渡している。
     * この時のパスはroute:listのパスではなく、URLにある一般的なパス。
     * 
     * toメソッドはパラメータで受け取ったパスから新しくリダイレクトのレスポンスを作成する。
     * ちなみにリダイレクトのHTTPコードは302
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $this->todo->find($id)->fill($input)->save();
        return redirect()->to('todo');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * delete()は物理削除のメソッド。
     * Laravelでは割と物理削除が簡単にできてしまう。
     * 論理削除にしたい場合、migrationファイルを作成するとき、
     * Illuminate\Database\Eloquent\SoftDeletesトレイトをModelに対して定義する。
     * そうするとtableにdeleted_atカラムが追加され、論理削除が可能になる。
     * （classの外で）use Illuminate\Database\Eloquent\SoftDeletes;
     * （classの中で）use SoftDeletes;
     * 
     */
    public function destroy($id)
    {
        $this->todo->find($id)->delete();
        return redirect()->to('todo');
    }
}
