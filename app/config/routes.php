<?php
// return array(
// 	'_root_'  => 'welcome/index',  // The default route
// 	'_404_'   => 'welcome/404',    // The main 404 route
	
// 	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
// );

return [
    '_root_' => 'auth/login', // 初期画面
    'words/add' => 'words/add',
    'words/index' => 'words/index',
    'words/correct_count' => 'words/correct_count', // 正答数取得API
];