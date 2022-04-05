<?php
/* Smarty version 4.0.0, created on 2022-01-12 14:17:13
  from '/home/dh_m6zq7r/mutiny-guild.com/templates/navbar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.0.0',
  'unifunc' => 'content_61df2939b4cc27_41451995',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'febebaa82e48be0444af1decaefa0682c9d42171' => 
    array (
      0 => '/home/dh_m6zq7r/mutiny-guild.com/templates/navbar.tpl',
      1 => 1642015029,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61df2939b4cc27_41451995 (Smarty_Internal_Template $_smarty_tpl) {
?><nav class="navbar navbar-expand-md navbar-light bg-light" style="margin-bottom: 24px 0;">
	<a class="navbar-brand" href="https://www.mutiny-guild.com"><img src="https://www.mutiny-guild.com/images/discord-shield.png" alt="Mutiny Home"></a>
	<ul class="navbar-nav mr-auto">
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Information
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="index.php?section=about">About Mutiny</a>
				<div class="dropdown-divider"></div>
				<!--<a class="dropdown-item" href="index.php?section=recruitment">Recruitment</a>-->
				<a class="dropdown-item" href="index.php?section=progression">Progression</a>
				<!--<div class="dropdown-divider"></div>-->
				<!--<a class="dropdown-item" href="#">Contact</a>-->
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="https://classic.warcraftlogs.com/guild/reports-list/510738/" target="_blank">Raid History</a>
        	</div>
      	</li>
		<?php if ($_smarty_tpl->tpl_vars['logged_in']->value) {?>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Guild Resources
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="https://www.mutiny-guild.com/index.php?section=ksk">KSK Loot</a>
        	</div>	
      	</li>
		<?php }?>
	</ul>
	<ul class="navbar-nav navbar-right">
		<?php if ($_smarty_tpl->tpl_vars['logged_in']->value) {?>
			<form class="form-inline my-2 my-sm-0" role="form" method="POST" action="index.php">
			<input type="hidden" name="do" value="logout" />
			<div class="text-dark" style="padding-right: 15px;">Logged in as: <strong><?php echo $_smarty_tpl->tpl_vars['member_name']->value;?>
</strong></div>
			<button class="btn btn-success my-2 my-md-0" type="submit">Logout</button>
			</form>
		<?php } else { ?>
			<form class="form-inline my-2 my-sm-0" role="form" method="POST" action="index.php">
			<input type="hidden" name="do" value="login" />
			<button class="btn btn-success my-2 my-md-0" type="submit">Login</button>
			</form>
		<?php }?>
	</ul>
</nav><?php }
}
