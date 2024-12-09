<?php

class Model_Word
{
    // すべての単語を取得
    public static function get_all_words()
    {
        return DB::select()->from('words')->execute()->as_array();
    }

    // 単語を追加
    public static function add_word($word, $translation)
    {
        try {
                return DB::insert('words')->set([
                    'word'        => $word,
                    'translation' => $translation,
                    'correct'     => 0, // 初期値を設定
                ])->execute();
            } catch (Database_Exception $e) {
            Log::error('単語の追加に失敗しました: ' . $e->getMessage());
            return false;
            }   
    }

    // wordsテーブルにcorrectを加えた後の修正

    // データベース接続設定
    private static function get_db()
    {
        return Database_Connection::instance();
    }

    public static function get_word_by_id($id)
    {
        $result = DB::select()
            ->from('words')
            ->where('id', '=', $id)
            ->execute()
            ->as_array();

        return $result ? $result[0] : null;
    }

    public static function update_correct($id, $is_correct)
    {
        try {
            // 更新クエリを実行
            $result = DB::update('words')
                ->set([
                    'correct' => $is_correct ? 1 : 0,
                    'updated_at' => date('Y-m-d H:i:s'),
                ])
                ->where('id', '=', $id)
                ->execute();

            // 更新件数を確認
            return $result > 0;
        } catch (Exception $e) {
            // エラーをログに記録
            Log::error('Failed to update correct status: ' . $e->getMessage());
            return false;
        }
    }

}
