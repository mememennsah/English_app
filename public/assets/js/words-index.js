function ViewModel() {
    var self = this;

    // ... (これまでのコード)

    // ランダムなメッセージを取得する関数
    self.getRandomMessage = function(isCorrect) {
        const messages = {
            correct: ['ナイス！', 'いいね！', '素晴らしい！'],
            incorrect: ['もっと熱くなれよ！', '俺でなきゃ見逃しちゃうね', 'もう一度チャレンジ！']
        };
        const randomIndex = Math.floor(Math.random() * messages[isCorrect ? 'correct' : 'incorrect'].length);
        return messages[isCorrect ? 'correct' : 'incorrect'][randomIndex];
    };

    // メッセージを表示する変数
    self.message = ko.observable('');

    // 単語を「知らない」とマークする
    self.markUnknown = function() {
        // ... (これまでのコード)
        self.message(self.getRandomMessage(false));
    };

    // 単語を「知っている」とマークする
    self.markKnown = function() {
        // ... (これまでのコード)
        self.message(self.getRandomMessage(true));
    };
}