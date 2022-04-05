<?php
/* Smarty version 4.0.0, created on 2022-01-12 18:39:06
  from '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk-changeraid.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.0.0',
  'unifunc' => 'content_61df669a845435_03988671',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a971a84593af01a7c88bca0322be4b23956e9afe' => 
    array (
      0 => '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk-changeraid.tpl',
      1 => 1642030742,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61df669a845435_03988671 (Smarty_Internal_Template $_smarty_tpl) {
?><div style="padding-top: 60px;">
    <div class="row about-title" style="display: block;">
        <div class="col">
            <strong>Change Raid Members</strong> 
        </div>
    </div>
	<div class="row about-section" style="padding-top: 15px;">
		<div class="col-md-5">
			<div class="row">
				<div class="col-md text-light bg-dark text-center">All KSK Members</div>
			</div>
			<div class="row overflow-auto" style="height: 450px;">
				<div class="col-md">
					<ul id="full_list">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pool']->value, 'player');
$_smarty_tpl->tpl_vars['player']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['player']->value) {
$_smarty_tpl->tpl_vars['player']->do_else = false;
?>
						<div id="player_<?php echo $_smarty_tpl->tpl_vars['player']->value['uid'];?>
" class="text-left" style="cursor: pointer;" onclick="MovePlayer('player_<?php echo $_smarty_tpl->tpl_vars['player']->value['uid'];?>
');">
						<li id=player_<?php echo $_smarty_tpl->tpl_vars['player']->value['uid'];?>
_name><?php echo $_smarty_tpl->tpl_vars['player']->value['name'];?>
</li>
						</div>
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>		
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-5">
			<div class="row">
				<div class="col-md text-light bg-dark text-center">Raid/Reserve Members</div>
			</div>
			<div class="row overflow-auto" style="height: 450px;">
				<div class="col-md">
					<ul id="active_list">	
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="padding-top: 5px;">
		<div class="col-md-12 text-light bg-dark text-center">
			<form role="form" method="POST" action="index.php">
			<select style="display: none;" id="raid_list_select" name="raid[]" multiple >
			<?php if ($_smarty_tpl->tpl_vars['raid_in_progress']->value == 1) {?>
				<input type="hidden" name="resume" value="true" />
				<input type="hidden" name="resume_raid_id" value="<?php echo $_smarty_tpl->tpl_vars['in_progress_raid_id']->value;?>
" />
			<?php }?>
			<input type="hidden" name="otp" value="<?php echo $_smarty_tpl->tpl_vars['oneTimeRaidId']->value;?>
" />
			<input type="hidden" name="do" value="confirmmemberchange" />
			<input type="hidden" name="list" value="3" />
			<button class="btn btn-success my-2 my-md-0" type="submit">Confirm Raid Members</button></form>
		</div>	
	</div>
</div>


<?php echo '<script'; ?>
 src="https://www.mutiny-guild.com/scripts/raid.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
<!--
$(document).ready(function() {
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['in_progress_raid_members']->value, 'member');
$_smarty_tpl->tpl_vars['member']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['member']->value) {
$_smarty_tpl->tpl_vars['member']->do_else = false;
?>
	MovePlayer('player_<?php echo $_smarty_tpl->tpl_vars['member']->value['user_id'];?>
')
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
})
-->
<?php echo '</script'; ?>
><?php }
}
