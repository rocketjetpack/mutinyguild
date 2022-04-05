<?php
/* Smarty version 4.0.0, created on 2022-01-10 20:42:35
  from '/home/dh_m6zq7r/mutiny-guild.com/templates/structure.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.0.0',
  'unifunc' => 'content_61dce08bb35896_28676681',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '042e2df35da886fede8588516ef62569110cc7ef' => 
    array (
      0 => '/home/dh_m6zq7r/mutiny-guild.com/templates/structure.tpl',
      1 => 1641760082,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:navbar.tpl' => 1,
    'file:error.tpl' => 1,
    'file:success.tpl' => 1,
    'file:index.tpl' => 1,
  ),
),false)) {
function content_61dce08bb35896_28676681 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
 	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://www.mutiny-guild.com/base.css">
		<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"><?php echo '</script'; ?>
> 
		<?php echo '<script'; ?>
 src="https://ksk.mutiny-guild.com/scripts/items.js"><?php echo '</script'; ?>
> 
	</head>
	<body>
		<div class="container-fullwidth">
			<div class="row opacity-90">
				<div class="col-md-12">
					<?php $_smarty_tpl->_subTemplateRender("file:navbar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
				</div>
				<?php if ($_smarty_tpl->tpl_vars['alert_error']->value) {?>
					<?php $_smarty_tpl->_subTemplateRender('file:error.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['alert_success']->value) {?>
					<?php $_smarty_tpl->_subTemplateRender('file:success.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
				<?php }?>
			</div>
			<div class="content-body">
				<?php if ((isset($_smarty_tpl->tpl_vars['template']->value))) {?>
					<?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['template']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
				<?php } else { ?>
					<?php $_smarty_tpl->_subTemplateRender('file:index.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
				<?php }?>
			</div>
		</div>
	</body>
</html>
<?php }
}
