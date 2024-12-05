<?php

class Model_User
{
    // ユーザーをすべて取得
    public static function get_all_users()
    {
        return DB::select()->from('users')->execute()->as_array();
    }

    // 特定のユーザーを取得
    public static function get_user($id)
    {
        return DB::select()->from('users')->where('id', $id)->execute()->current();
    }

    // ユーザーを追加
    public static function add_user($name, $password, $email)
    {
        return DB::insert('users')->set([
            'name'  => $name,
            'password' => $password,
            'email' => $email,
        ])->execute();
    }

    
}
