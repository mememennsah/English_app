<?php
// Bootstrap the framework DO NOT edit this
require COREPATH.'bootstrap.php';

\Autoloader::add_classes(array(
	// Add classes you want to override here
	// Example: 'View' => APPPATH.'classes/view.php',
));

// Register the autoloader
\Autoloader::register();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGING
 * Fuel::PRODUCTION
 */
\Fuel::$env = \Arr::get($_SERVER, 'FUEL_ENV', \Arr::get($_ENV, 'FUEL_ENV', \Fuel::DEVELOPMENT));

// Initialize the framework with the config file.
\Fuel::init('config.php');

// Model_User_Word クラスを手動でロード
Autoloader::add_classes([
    'Model_User_Word' => APPPATH . 'classes/model/user_word.php',
]);

// Config::load() を使用して設定を読み込み
Config::load('myconfig', true); // true は設定を独自のグループにロードする
$site_name = Config::get('site_name'); // "My Awesome App" を取得
$items_per_page = Config::get('items_per_page'); // 20 を取得

// 設定値をプログラムから変更する場合
// Config::set('myconfig.items_per_page', 50);
// $new_value = Config::get('myconfig.items_per_page'); // 50 を取得

