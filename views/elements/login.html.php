<?php if ($loginUrl): ?>
	<fb:login-button scope="email"><?= $t('Login/Register with Facebook'); ?></fb:login-button>
<?php else: ?>
	<?= $this->html->link($t('Facebook Logout'), $logoutUrl); ?>
<?php endif; ?>