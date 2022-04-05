<?php
/* Smarty version 4.0.0, created on 2022-01-10 20:52:39
  from '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk-startraid.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.0.0',
  'unifunc' => 'content_61dce2e75f62c7_18853994',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca1482a3e35e955eae4c00ca6a6fd1975e77fd52' => 
    array (
      0 => '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk-startraid.tpl',
      1 => 1641865956,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61dce2e75f62c7_18853994 (Smarty_Internal_Template $_smarty_tpl) {
?><div style="padding-top: 60px;">
    <div class="row about-title" style="display: block;">
        <div class="col">
            <strong>Start Raid</strong> 
        </div>
    </div>
    <div class="row about-section opacity-90">
        <div class="col">
            Use this form to begin a raid.  All members on the KSK list are displayed on the left.  Click any members who should be considered present (in raid and reserve) to move them to the right.
			Enter a raid name, and hit Start Raid to proceed to the raid/loot management page.
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
" class="text-left" style="cursor: pointer;" <?php if ($_smarty_tpl->tpl_vars['raid_in_progress']->value == 0) {?>onclick="MovePlayer('player_<?php echo $_smarty_tpl->tpl_vars['player']->value['uid'];?>
');"<?php }?>>
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
			<input type="hidden" name="do" value="handle_loot" />
			<input type="hidden" name="list" value="3" />
			<input id="inp_raid_name" <?php if ($_smarty_tpl->tpl_vars['raid_in_progress']->value) {?>readonly="readonly"<?php }?> name="raid_name" placeholder="Enter a Raid Name" />&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-success my-2 my-md-0" type="submit"><?php if ($_smarty_tpl->tpl_vars['raid_in_progress']->value == 0) {?>Start Raid<?php } else { ?>Resume Raid<?php }?></button></form>
		</div>	
	</div>
</div>


<?php echo '<script'; ?>
 src="https://ksk.mutiny-guild.com/scripts/raid.js"><?php echo '</script'; ?>
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
	MovePlayer('player_<?php echo $_smarty_tpl->tpl_vars['member']->value;?>
')
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	document.getElementById("inp_raid_name").value = "<?php echo $_smarty_tpl->tpl_vars['in_progress_raid_name']->value;?>
";
})
-->
<?php echo '</script'; ?>
><?php }
}
