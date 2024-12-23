<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/words-index.css">
    <script type='text/javascript' src='/assets/js/knockout-3.5.1.js'></script>
    <script src="/assets/js/app.js" defer></script>
    <title>英単語帳</title>
    
</head>
<body>
    <div class="container">
        <h1>英単語帳</h1>

        <!-- フラッシュメッセージの表示 -->
        <?php if (Session::get_flash('success')): ?>
            <div class="flash-message flash-success">
                <?php echo Session::get_flash('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (Session::get_flash('error')): ?>
            <div class="flash-message flash-error">
                <?php echo Session::get_flash('error'); ?>
            </div>
        <?php endif; ?>

        <!-- 正答数の表示部分 -->
        <div class="correct-count-section">
        <h2>知ってる単語</h2>
        <p class="correct-count" data-bind="text: correctCount">0</p>
        </div>

        <!-- 単語情報 -->
        <div class="word-box">
            <p class="label">英単語:</p>
            <p><?= isset($word['word']) ? htmlspecialchars($word['word']) : '---'; ?></p>

            <p class="label">日本語訳:</p>
            <p><?= isset($word['translation']) ? htmlspecialchars($word['translation']) : '---'; ?></p>

            <p class="label">正誤判定:</p>
            <p class="result <?= isset($word['correct']) && $word['correct'] ? 'ok' : 'ng'; ?>">
                <?= isset($word['correct']) ? ($word['correct'] ? '○' : '×') : '---'; ?>
            </p>
        </div>

        <!-- ボタン群 -->
        <div class="buttons">
            <!-- 戻るボタン -->
            <form action="back" method="post" style="display: inline;">
                <button type="submit" class="back-btn" title="前の単語に戻る">前へ</button>
                <!-- CSRF対策 -->
                <?= Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>
            </form>

            <!-- 知らないボタン -->
            <form action="unknown" method="post" style="display: inline;">
                <input type="hidden" name="id" value="<?= isset($word['id']) ? htmlspecialchars($word['id']) : ''; ?>">
                <!-- CSRF対策 -->
                <?= Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>
                <button type="submit" title="単語を知らないとして記録する">知らない</button>
                <tr>
            </form>

            <!-- 知ってる！ボタン -->
            <form action="known" method="post" style="display: inline;">
                <input type="hidden" name="id" value="<?= isset($word['id']) ? htmlspecialchars($word['id']) : ''; ?>">
                <!-- CSRF対策 -->
                <?= Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>
                <button type="submit" title="単語を知っているとして記録する">知ってる！</button>
                <tr>
            </form>

            <!-- 次へボタン -->
            <form action="next" method="post" style="display: inline;">
                <button type="submit" title="次の単語を表示する">次へ</button>
                <!-- CSRF対策 -->
                <?= Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>
            </form>
        </div>   
    </div>

    <div>
    <!-- 単語追加ボタン -->    
        <form action="add" method="post">
            <button type="submit" title="単語を追加">単語を追加</button>
            <!-- CSRF対策 -->
            <?= Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>
        </form>    
    </div>

</body>
</html>


