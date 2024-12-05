<h2>プロフィール更新</h2>

<?php if (Session::get_flash('success')): ?>
    <p style="color: green;"><?php echo Session::get_flash('success'); ?></p>
<?php elseif (Session::get_flash('error')): ?>
    <p style="color: red;"><?php echo Session::get_flash('error'); ?></p>
<?php endif; ?>

<?php echo Form::open(['action' => 'auth/update_profile', 'method' => 'post']); ?>

    <p>
        <?php echo Form::label('メールアドレス', 'email'); ?>
        <?php echo Form::input('email', Auth::get('email'), ['required' => true, 'type' => 'email']); ?>
    </p>

    <p>
        <?php echo Form::label('プロフィールデータ', 'profile'); ?>
        <?php echo Form::textarea('profile', json_encode(Auth::get('profile_fields')), ['rows' => 5, 'cols' => 50]); ?>
    </p>

    <p>
        <?php echo Form::submit('submit', '更新'); ?>
    </p>

<?php echo Form::close(); ?>
