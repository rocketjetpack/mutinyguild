<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
 	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://www.mutiny-guild.com/base.css">
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script> 
		<script src="https://ksk.mutiny-guild.com/scripts/items.js"></script> 
	</head>
	<body>
		<div class="container-fullwidth">
			<div class="row opacity-90">
				<div class="col-md-12">
					{include file="navbar.tpl"}
				</div>
				{if $alert_error}
					{include file='error.tpl'}
				{/if}
				{if $alert_success}
					{include file='success.tpl'}
				{/if}
			</div>
			<div class="content-body">
				{if isset($template)}
					{include file=$template}
				{else}
					{include file='index.tpl'}
				{/if}
			</div>
		</div>
	</body>
</html>
