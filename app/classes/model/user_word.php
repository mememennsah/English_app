<?php
class Model_User_Word
{
    


    public static function get_user_words($user_id)
    {
        return DB::select('user_words.*', 'words.word', 'words.translation')
            ->from('user_words')
            ->join('words', 'INNER')
            ->on('user_words.word_id', '=', 'words.id')
            ->where('user_words.user_id', $user_id)
            ->execute()
            ->as_array();
    }

    
    public static function add_user_word($user_id, $word_id, $status = 0)
    {
        try {
            // 入力値をログに記録
            Log::info('add_user_word called with parameters: user_id=' . print_r($user_id, true) . ', word_id=' . print_r($word_id, true) . ', status=' . print_r($status, true));

            $user_id = $user_id;
            $word_id = $word_id;
            $status = $status;


            // 重複チェック
            $existing = DB::select()
                ->from('user_words')
                ->where('user_id', $user_id)
                ->and_where('word_id', $word_id)
                ->execute()
                ->as_array();

            if (!empty($existing)) {
                Log::info('Duplicate entry: user_id=' . $user_id . ', word_id=' . $word_id);
                return false;
            }

            DB::start_transaction();

            DB::insert('user_words')->set([
                'user_id' => $user_id,
                'word_id' => $word_id,
                'status'  => $status,
            ])->execute();

            DB::commit_transaction();

            return true;
        } catch (Exception $e) {
            DB::rollback_transaction();
            Log::error('Failed to add user word: ' . $e->getMessage());
            return false;
        }
    }

    public static function update_status($user_word_id, $status)
    {
        try {
            if (!is_int($user_word_id) || $user_word_id <= 0) {
                throw new InvalidArgumentException('Invalid user_word_id: ' . print_r($user_word_id, true));
            }
            if (!in_array($status, [0, 1], true)) {
                throw new InvalidArgumentException('Invalid status: ' . print_r($status, true));
            }
    
            $result = DB::update('user_words')
                ->set(['status' => $status])
                ->where('id', $user_word_id)
                ->execute();
    
            if ($result === 0) {
                Log::info('No rows updated for user_word_id: ' . $user_word_id);
                return false;
            }
    
            return true;
        } catch (InvalidArgumentException $e) {
            Log::error('Invalid parameter provided: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            Log::error('Failed to update status: ' . $e->getMessage());
            return false;
        }
    }
    
}
