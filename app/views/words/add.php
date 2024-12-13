<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>単語追加</title>
</head>
<body>
    <h1>新しい単語を追加</h1>

    <?php if (Session::get_flash('error')): ?>
        <p style="color: red;"><?php echo Session::get_flash('error'); ?></p>
    <?php endif; ?>

    <form action="create" method="post">
        <!-- CSRFトークンの埋め込み -->
        <input type="hidden" name="<?= Config::get('security.csrf_token_key') ?>" value="<?= Security::fetch_token() ?>">
        
        <p>
            <label for="word">単語:</label>
            <input type="text" name="word" id="word" required>
        </p>
        <p>
            <label for="translation">翻訳:</label>
            <input type="text" name="translation" id="translation" required>
        </p>
        <p>
            <button type="submit">追加</button>
        </p>
    </form>

    <!-- <p><a href="words/index">戻る</a></p> -->
    <form action="index" method="post">
        <button type="submit" title="前のページに戻る">戻る</button>
        <!-- CSRFトークンの埋め込み -->
        <input type="hidden" name="<?= Config::get('security.csrf_token_key') ?>" value="<?= Security::fetch_token() ?>">
    </form>
</body>
</html>
