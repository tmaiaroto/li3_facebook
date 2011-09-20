<div id="fb-root"></div>
<?php if(!$async): ?>
	<script src="http://connect.facebook.net/<?=$locale?>/<?=$script?>"></script>
	<script>
		FB.init({appId: '<?=$appId?>', status: true, cookie: true, xfbml: true }); 
	</script>
<?php else: ?>
<script>
	window.fbAsyncInit = function() {
	  FB.init({
	    appId: '<?=$appId?>',
	    cookie: true,
	    xfbml: true,
	    oauth: true
	  });
	  FB.Event.subscribe('auth.login', function(response) {
	    window.location.reload();
	  });
	  FB.Event.subscribe('auth.logout', function(response) {
	    window.location.reload();
	  });
	};
	(function() {
	  var e = document.createElement('script'); e.async = true;
	  e.src = document.location.protocol +
	    '//connect.facebook.net/<?=$locale?>/all.js';
	  document.getElementById('fb-root').appendChild(e);
	}());

	
	</script>
<?php endif; ?>
