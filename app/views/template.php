<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'English Learning App' ?></title>
</head>
<body>
    <header>
        <h1>英単語帳アプリ</h1>
    </header>
    <main>
        <?= isset($content) ? $content : '' ?>
    </main>
    <footer>
        <p>&copy; 2024 英単語帳アプリ</p>
    </footer>
</body>
</html>
