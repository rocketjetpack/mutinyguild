<?php
/* Smarty version 4.0.0, created on 2022-01-11 05:51:53
  from '/home/dh_m6zq7r/mutiny-guild.com/templates/error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.0.0',
  'unifunc' => 'content_61dd61492256a2_48040985',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f86ddab24ce3ff82c66ddda5cb68be8c2a429654' => 
    array (
      0 => '/home/dh_m6zq7r/mutiny-guild.com/templates/error.tpl',
      1 => 1641764348,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61dd61492256a2_48040985 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="alert alert-popup-top alert-danger" style="z-index: 99;">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<?php echo $_smarty_tpl->tpl_vars['error_message']->value;?>

</div><?php }
}
