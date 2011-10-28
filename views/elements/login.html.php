<?php if ($loginUrl): ?>
	<!--<fb:login-button scope="email">Login with Facebook</fb:login-button>-->
	<?= $this->html->link($t('Facebook Login'), $loginUrl);?>
<?php else: ?>
	<?= $this->html->link($t('Facebook Logout'), $logoutUrl); ?>
<?php endif; ?>