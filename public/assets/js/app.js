document.addEventListener("DOMContentLoaded", () => {
    function AppViewModel() { 
        const self = this; 
    
        // 正答数のバインド用プロパティ
        self.correctCount = ko.observable(0);
        self.correctMessage = ko.computed(() => `${self.correctCount()}問正解`);
    
        // APIから正答数を取得する非同期関数
        self.fetchCorrectCount = async function () {
            try {
                const response = await fetch('/words/correct_count', {
                    method: 'GET',
                    headers: { 
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('取得した正答数:', data.correct_count); // デバッグ用ログ
                self.correctCount(data.correct_count || 0); // 正答数を更新
            } catch (error) {
                console.error('正答数の取得に失敗しました:', error);
            }
        };
    
        // 初期化時に正答数を取得
        self.fetchCorrectCount();
    }

    // Knockout.jsをバインド
    const viewModel = new AppViewModel();
    ko.applyBindings(viewModel);
});
