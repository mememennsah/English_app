<?php

class Controller_Auth extends Controller
{
    // ログイン画面を表示
    public function action_login()
    {
        if (Input::method() == 'POST') {
            $username = Input::post('username');
            $password = Input::post('password');
    
            // デバッグ: 入力値の確認
            Log::debug('Username: ' . $username);
            Log::debug('Password: ' . $password);
    
            if (Auth::login($username, $password)) {
                Log::debug('Login successful.');
                Session::set_flash('success', 'ログインしました！');
                Response::redirect('words/index');
            } else {
                Log::debug('Login failed.');
                Session::set_flash('error', 'ユーザー名またはパスワードが間違っています。');
            }
        }
    
        return View::forge('auth/login');
    }
    

    public function action_register()
    {
        if (Input::method() == 'POST') {
            $username = Input::post('username');
            $password = Auth::hash_password(Input::post('password'));
            $email = Input::post('email');

            // 現在のタイムスタンプ
            $current_time = time();

            try {
                // DBに挿入
                DB::insert('users')->set([
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'group' => 1, // デフォルトグループ
                    'profile_fields' => json_encode([]),
                    'last_login' => 0,
                    'login_hash' => '',
                    'created_at' => $current_time,
                    'updated_at' => $current_time,
                ])->execute();

                Session::set_flash('success', 'ユーザー登録が完了しました！');
                Response::redirect('auth/login');
            } catch (Exception $e) {
                Session::set_flash('error', '登録中にエラーが発生しました: ' . $e->getMessage());
            }
        }

        return View::forge('auth/register');
    }

    public function action_update_profile()
    {
        if (Input::method() == 'POST') {
            $user_id = Auth::get_user_id()[1]; // 現在のログインユーザーのID
            $email = Input::post('email');
            $profile_data = json_encode(Input::post('profile'));
    
            // 現在のタイムスタンプ
            $current_time = time();
    
            try {
                // DBを更新
                DB::update('users')->set([
                    'email' => $email,
                    'profile_fields' => $profile_data,
                    'updated_at' => $current_time,
                ])->where('id', '=', $user_id)->execute();
    
                Session::set_flash('success', 'プロフィールが更新されました！');
                Response::redirect('/');
            } catch (Exception $e) {
                Session::set_flash('error', '更新中にエラーが発生しました: ' . $e->getMessage());
            }
        }
    
        return View::forge('auth/update_profile');
    }
    


    // ログアウト処理
    public function action_logout()
    {
        Auth::logout();
        Session::set_flash('success', 'ログアウトしました！');
        Response::redirect('/auth/login');
    }
}