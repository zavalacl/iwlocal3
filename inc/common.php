<link type="text/css" rel="stylesheet" href="/css/global.css" />
<link rel="shortcut icon" href="/img/favicon.ico" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.migrate.js"></script>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/js/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript" src="/js/misc.js"></script>
<?php
	if(isset($error_fields) && $error_fields){
?>
<script type="text/javascript">
$(document).ready(function(){
<?php
	foreach($error_fields as $error){
?>
$('#<?php echo $error; ?>').css('backgroundColor','#eea399');
$('#<?php echo $error; ?>').css('color','#9a3528');
<?php
	}
?>
});
</script>
<?php
	}
?>
