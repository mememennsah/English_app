<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
    <h1>ログイン</h1>

    <?php if (Session::get_flash('error')): ?>
        <p style="color: red;"><?php echo Session::get_flash('error'); ?></p>
    <?php endif; ?>

    <form action="/auth/login" method="post">
        <!-- CSRFトークンの埋め込み -->
        <input type="hidden" name="<?= Config::get('security.csrf_token_key') ?>" value="<?= Security::fetch_token() ?>">

        <p>
            <label for="username">ユーザー名:</label>
            <input type="text" name="username" id="username" required>
        </p>
        <p>
            <label for="password">パスワード:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <button type="submit">ログイン</button>
        </p>
    </form>

    <p><a href="/auth/register">新規登録はこちら</a></p>
</body>
</html>
