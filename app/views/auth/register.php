<h2>ユーザー登録</h2>

<?php if (Session::get_flash('error')): ?>
    <p style="color: red;"><?php echo Session::get_flash('error'); ?></p>
<?php endif; ?>

<?php echo Form::open(['action' => 'auth/register', 'method' => 'post']); ?>

    <!-- CSRFトークンの埋め込み -->
    <input type="hidden" name="<?= Config::get('security.csrf_token_key') ?>" value="<?= Security::fetch_token() ?>">

    <p>
        <?php echo Form::label('ユーザー名', 'username'); ?>
        <?php echo Form::input('username', Input::post('username'), ['required' => true]); ?>
    </p>

    <p>
        <?php echo Form::label('メールアドレス', 'email'); ?>
        <?php echo Form::input('email', Input::post('email'), ['required' => true, 'type' => 'email']); ?>
    </p>

    <p>
        <?php echo Form::label('パスワード', 'password'); ?>
        <?php echo Form::password('password', '', ['required' => true]); ?>
    </p>

    <p>
        <?php echo Form::submit('submit', '登録'); ?>
    </p>

    <p><a href="login">戻る</a></p>

<?php echo Form::close(); ?>
