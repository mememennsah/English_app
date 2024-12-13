<?php


class Controller_Words extends Controller
{

    public function before()
    {
        parent::before();

         $this->user_id = Session::get('user_id');
        // 現在のURLをデバッグ
        Log::debug('Current URI: ' . Uri::string());

        // ログイン状態を確認
        Log::debug('Auth::check(): ' . (Auth::check() ? 'Logged in' : 'Not logged in'));


        if (!Auth::check() && strpos(Uri::string(), 'auth/login') === false) {
            Log::debug('Redirecting to auth/login');
            Session::set_flash('error', 'ログインが必要です。');
            Response::redirect('auth/login');
        }
  
    }

    
    public function action_index()
    {
        // 現在の単語 ID をセッションから取得（デフォルトで1）
        $current_id = Session::get('current_word_id', 1);

        // 指定された ID の単語を取得
        $word = Model_Word::get_word_by_id($current_id);

        // 単語が存在しない場合（最後の単語の次）
        if (!$word) {
            Session::set_flash('info', 'これ以上単語はありません。');
            // Response::redirect('words/index'); // 必要であれば終了時の処理に変更
        }

        // 単語データをビューに渡す
        return View::forge('words/index', ['word' => $word]);
    }


        // 単語追加フォームの表示
    public function action_add()
    {
        return View::forge('words/add');
    }

    // 単語追加フォームのバリデーション
    private function validate_word_form($data)
    {
        $validation = Validation::forge();

        // "word" のバリデーション
        $validation->add('word', '単語')
            ->add_rule('required')           // 必須チェック
            ->add_rule('max_length', 255);  // 最大文字数

        // "translation" のバリデーション
        $validation->add('translation', '翻訳')
            ->add_rule('required')           // 必須チェック
            ->add_rule('max_length', 255);  // 最大文字数

        return $validation;
    }

    // 単語の追加処理
    public function action_create()
    {
        // CSRF対策
        if (Input::method() === 'POST' && !Security::check_token()) {
            Session::set_flash('error', '不正なリクエストです。');
            Response::redirect('words/index'); // 不正リクエストの場合リダイレクト
        }

        // セッションからログイン中のユーザーIDを取得
        $user_id = Session::get('user_id');

        // フォームから送信されたデータを取得
        $data = [
            'word' => Input::post('word'),
            'translation' => Input::post('translation'),
        ];

        // バリデーション処理
        $validation = $this->validate_word_form($data);

        if ($validation->run()) {
            try {
                // 1. `words` テーブルに単語を追加
                $word_result = Model_Word::add_word($data['word'], $data['translation']);

                if ($word_result) {
                    // 2. 追加された単語のIDを取得
                    $word_id = $word_result[0]; // INSERT文の結果から挿入されたIDを取得

                    // 3. `user_words` テーブルにユーザーと単語を紐付け
                    $user_word_result = Model_User_Word::add_user_word($user_id, $word_id);

                    if ($user_word_result) {
                        Session::set_flash('success', '単語を追加し、ユーザーに紐付けました！');
                        Response::redirect('words/index');
                    } else {
                        Session::set_flash('error', '単語の追加は成功しましたが、ユーザーとの紐付けに失敗しました。');
                    }
                } else {
                    Session::set_flash('error', '単語の追加に失敗しました。');
                }
            } catch (Exception $e) {
                // エラー時のログ出力
                Log::error('Error adding word: ' . $e->getMessage());
                Session::set_flash('error', '予期せぬエラーが発生しました。');
            }
        } else {
            // バリデーションエラー時の処理
            Session::set_flash('error', implode('<br>', $validation->error()));
        }

        // エラー時には再度フォームを表示
        return View::forge('words/add', ['data' => $data]);
    }




    public function action_back()
    {
        // CSRF対策
        if (!Security::check_token()) {
            Session::set_flash('error', '不正なリクエストです。');
            Response::redirect('words/index');
        }
        
        // 現在の単語 ID をセッションから取得（デフォルトで1）
        $current_id = Session::get('current_word_id', 1);

        // 前の単語 ID を設定（最小値1）
        $prev_id = max(1, $current_id - 1);

        // `$prev_id` が `$current_id - 1` でない場合、データベースから `$current_id` より小さい最大のIDを取得
        try {
            $result = DB::select('id')
                ->from('words')
                ->where('id', '<', $current_id)
                ->order_by('id', 'desc')
                ->limit(1)
                ->execute()
                ->as_array();

            if (!empty($result)) {
                $prev_id = $result[0]['id']; // 最大の前のIDを設定
            }
        } catch (Exception $e) {
            // エラーが発生した場合、ログに記録
            Log::error('Failed to fetch previous word ID: ' . $e->getMessage());
        }
        
        Session::set('current_word_id', $prev_id);

        // `action_index` にリダイレクト
        Response::redirect('words/index');
    }


        // 「知らない」ボタン処理
    public function action_unknown()
    {
        // CSRF対策
        if (!Security::check_token()) {
            Session::set_flash('error', '不正なリクエストです。');
            Response::redirect('words/index');
        }
        
        $this->update_word_status(false, '単語を「知らない」として記録しました。');
    }


    // 「知ってる！」ボタン処理
    public function action_known()
    {
        // CSRF対策
        if (!Security::check_token()) {
            Session::set_flash('error', '不正なリクエストです。');
            Response::redirect('words/index');
        }
        
        $this->update_word_status(true, '単語を「知ってる！」として記録しました。');
    }


    // 単語の状態を更新する共通メソッド
    private function update_word_status($is_correct, $success_message)
    {
        // CSRF対策
        if (!Security::check_token()) {
            Session::set_flash('error', '不正なリクエストです。');
            Response::redirect('words/index');
        }

        // POSTから単語IDを取得
        $id = Input::post('id');

        if ($id) {
            // モデルを使って正誤判定を更新
            if (Model_Word::update_correct($id, $is_correct)) {
                Session::set_flash('success', $success_message);
            } else {
                Session::set_flash('error', '単語の更新に失敗しました。');
            }
        } else {
            Session::set_flash('error', '単語IDが指定されていません。');
        }

        // 一覧ページにリダイレクト
        Response::redirect('words/index');
    }


    // 「次」ボタン処理
    public function action_next()
    {
        // CSRF対策
        if (!Security::check_token()) {
            Session::set_flash('error', '不正なリクエストです。');
            Response::redirect('words/index');
        }
        
        // 現在の単語 ID をセッションから取得（デフォルトで1）
        $current_id = Session::get('current_word_id', 2);

        // 次の単語 ID を設定
        $next_id = $current_id + 1;
        // `$next_id` が `$current_id + 1` でない場合、データベースから `$current_id` より大きい最小のIDを取得
        try {
            $result = DB::select('id')
                ->from('words')
                ->where('id', '>', $current_id)
                ->order_by('id', 'asc')
                ->limit(1)
                ->execute()
                ->as_array();

            if (!empty($result)) {
                $next_id = $result[0]['id']; // 最小の次のIDを設定
            }
        } catch (Exception $e) {
            // エラーが発生した場合、ログに記録
            Log::error('Failed to fetch next word ID: ' . $e->getMessage());
        }

            // 次の単語が存在しない場合（最後の単語の次）
        if ($next_id === null) {
            Session::set_flash('info', 'これ以上単語はありません。終了します。');
            Response::redirect('words/end'); // 終了処理のページまたはビュー
        }

        // 次の単語 ID をセッションに保存
        Session::set('current_word_id', $next_id);

        // `action_index` にリダイレクト
        Response::redirect('words/index');
    }

    public function action_correct_count()
    {
        try{
            // Model_Wordのメソッドを呼び出して正答数を取得
            $correct_count = Model_Word::get_correct_count();
            return Response::forge(json_encode(['correct_count' => $correct_count]), 200, ['Content-Type' => 'application/json']);
        } catch (Exception $e) {
            Log::error('Failed to fetch correct count: ' . $e->getMessage());    
        }
    }

    
}
