<?php 
/**
  extends：パラメータで受け取ったresources/views以下のディレクトリをドットで区切り、拡張子を除いたファイルを継承する。 
  section：Viewの一部分を定義する。閉じるには endsection, stop, showの三つがある。endsectionは定義のみ、showは定義と同時に表示も行う。イールドは、sectionのパラメータと自分のパラメータが一致した時にファイルを読み込む。
  今回は上でlayouts/appを読みこんでいるので、layouts/appのyield部分に定義したcontentが埋まる。 
*/
?>
@extends ('layouts.app')
@section ('content')

<h1 class="page-header">{{ $user->name }}のToDo一覧</h1>
<p class="text-right">
  <a class="btn btn-success" href="/todo/create">新規作成</a>
</p>
<table class="table">
  <thead class="thead-light">
    <tr>
      <th>ID</th>
      <th>やること</th>
      <th>作成日時</th>
      <th>更新日時</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php 
  /**
    TodoControllerのindexメソッドから受け渡された$todosはCollectionクラスのインスタンス。
    Collectionクラスは配列操作可能なオブジェクトで、便利なメソッドが沢山実装されている。
    なので、今回はforeachでも展開できている。

    ヘルパ関数のrouteメソッドでformタグに変換される時にroute:listにあるURIが生成され、そこからリンクをクリックした時に各アクションが呼ばれる。
    ちなみに、Routeで呼ばれた時にコントローラがインスタンス化されていて、そこからコンストラクタが動くイメージ。

    bladeはphpを直接かけるファイルである。
    HTML出力するとき{{}}と{!! !!}の二つがある。（厳密には{{{}}}を加えた三つ）
    {{}}はエスケープ処理を行い、文字出力をする。CSRF対策になるすごいやつ。
    phpファイルで書き直すなら<?php echo e(内容) ?> or <?= e() ?>
    {!! はエスケープ処理を行わず、出力をする !!}
    phpファイルで書き直すなら<?php echo 内容 ?> or <?= ?>
  */
  ?>

  @foreach ($todos as $todo)
    <tr>
      <td class="align-middle">{{ $todo->id }}</td>
      <td class="align-middle">{{ $todo->title }}</td>
      <td class="align-middle">{{ $todo->created_at }}</td>
      <td class="align-middle">{{ $todo->updated_at }}</td>
      <td><a class="btn btn-primary" href="{{ route('todo.edit', $todo->id) }}">編集</a></td>
      <td>
        <?php 
        /**
          ::はスコープ演算子と言われ、静的メソッドや静的なプロパティにアクセスするために使われる。
          クラスの名::アクセス先
          
          Form::でFormBuilderクラスのopenメソッドを呼び出している。
          FormBuilderのopenメソッドは、method属性がGet以外の時にTokenをhidden属性で信するinputタグを自動的に作ってくれる。
          参照：Git（https://github.com/laravel/framework/blob/4.2/src/Illuminate/Html/FormBuilder.php）
          パラメータはそれぞれhtmlのformタグでいうaction属性とmethod属性。
          
          POSTはデフォルトで設定されているため、記述がない場合もある。
          HTMLのformタグはmethod指定がないものは作れないため、get,put,post,patch,deleteのいずれかを指定する必要がある。

          Form::inputもFormBuilderクラスのメソッドの一つ。
          Form::input('TYPE属性', 'NAME属性（フィールド名）', 'VALUE属性（フィールド名の値）', ['その他のパラメータ'])
          クラスや必須属性等、その他の属性をつけたい時は、配列で各属性を渡せばOK。


          全体的な処理の流れは、Form::openでformタグが展開される。その時に各パラメータからroute:listに対応するURIがactioon属性に展開され、submitを押したときにそのURIに従って処理が流れていくイメージ。
          また、bladeファイルはクライアントで要求された時にコンパイルされるが、そのコンパイルされたファイルは\storage\framework\views以下に配置されている。

          また、アプリ側/Webサーバ側でLaravelが生成したURIの最後にindex.phpを自動的に最後に付け加えている。そっちの方が無駄がなく綺麗だから。
          設定している場所は、apacheのconfigファイル
        */
        ?>
        {!! Form::open(['route' => ['todo.destroy', $todo->id], 'method' => 'DELETE']) !!}
          {!! Form::submit('削除', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@endsection