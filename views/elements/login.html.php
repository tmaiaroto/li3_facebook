<?php if ($loginUrl): ?>
	<fb:login-button perms="email">Login with Facebook</fb:login-button>
<?php else: ?>
	<?= $this->html->link($t('Facebook Logout'), $logoutUrl); ?>
<?php endif; ?>