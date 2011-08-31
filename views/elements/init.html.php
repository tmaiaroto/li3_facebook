<div id="fb-root"></div>
<?php if(!$async): ?>
	<script src="http://connect.facebook.net/<?=$locale?>/<?=$script?>"></script>
	<script>
		FB.init({appId: '<?=$appId?>', status: true, cookie: true, xfbml: true}); 
	</script>
<?php else: ?>
<script>
	window.fbAsyncInit = function() { 
		FB.init({appId: '<?=$appId?>', status: true, cookie: true, xfbml: true}); 
	};
	
	(function() { 
		var e = document.createElement('script'); 
		e.async = true;
		e.src = document.location.protocol + '//connect.facebook.net/<?=$locale?>/<?=$script?>';
		document.getElementById('fb-root').appendChild(e); 
	}());	
	</script>
<?php endif; ?>