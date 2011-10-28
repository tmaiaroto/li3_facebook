<?php if ($loginUrl): ?>
<<<<<<< HEAD
	<fb:login-button scope="email"><?= $t('Login/Register with Facebook'); ?></fb:login-button>
=======
	<!--<fb:login-button scope="email">Login with Facebook</fb:login-button>-->
	<?= $this->html->link($t('Facebook Login'), $loginUrl);?>
>>>>>>> dev
<?php else: ?>
	<?= $this->html->link($t('Facebook Logout'), $logoutUrl); ?>
<?php endif; ?>