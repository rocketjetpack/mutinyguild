<?php
/* Smarty version 4.0.0, created on 2022-01-10 21:13:19
  from '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.0.0',
  'unifunc' => 'content_61dce7bf7b42d9_23037584',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3ce576d5dd43dcdcd9c13e7a520ee2f24c3d3a3e' => 
    array (
      0 => '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk.tpl',
      1 => 1641843166,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61dce7bf7b42d9_23037584 (Smarty_Internal_Template $_smarty_tpl) {
?><div style="padding-top: 60px; padding-left: 5px; padding-right: 5px;">
    <?php if ($_smarty_tpl->tpl_vars['isLootMaster']->value) {?>
    <div class="row border" style="width: 1200px; padding-bottom: 10px;">
        <div class="col-md-2 border">
            <div class="row" style="height: 43px;"><div class="col bg-light opacity-100 text-center" style="padding: 5px;"><strong>KSK Controls</strong></div></div>
        </div>
        <div class="col-md border">
            <div class="row" style="height: 43px;">
                <div class="col bg-light opacity-100 text-center" style="padding: 5px;"><a href='index.php?section=ksk&action=import' class="btn-sm btn-primary" type="button">Import List</a></div>
                <div class="col bg-light opacity-100 text-center" style="padding: 5px;"><a href='index.php?section=ksk&action=addmember' class="btn-sm btn-primary" type="button">Add Member</a></div>
                <div class="col bg-light opacity-100 text-center" style="padding: 5px;"><a href='index.php?section=ksk&action=startraid' class="btn-sm btn-primary" type="button">Start A Raid</a></div>
            </div>
        </div>
    </div>
    <?php }?>
    <div class="row" style="width: 1200px;">
        <div class="col-md-2 border">
            <div class="row"><div class="col bg-light opacity-100 text-center" style="padding: 5px;"><strong>List Order</strong></div></div>
        </div>
        <div class="col-md border">
            <div class="row"><div class="col bg-light opacity-100 text-center" style="padding: 5px;"><strong>Loot History</strong></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 bg-light opacity-90 overflow-auto" style="height: 600px;">
            <ul class="list-group">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pool']->value, 'player');
$_smarty_tpl->tpl_vars['player']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['player']->value) {
$_smarty_tpl->tpl_vars['player']->do_else = false;
?>
               <li class="list-group-item" style="padding: 5px; border: none;"><?php echo $_smarty_tpl->tpl_vars['player']->value['pos'];?>
. <?php echo $_smarty_tpl->tpl_vars['player']->value['name'];?>
</li>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </ul>
        </div>
        <div class="col-md bg-light opacity-90 overflow-auto" style="height: 600px;">
            <?php if ($_smarty_tpl->tpl_vars['lootlogentries']->value == 0) {?>
            <div class="row justify-content-center ">
                <div class="col-md">No Loot History</div>
            </div>
            <?php } else { ?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'log_entry');
$_smarty_tpl->tpl_vars['log_entry']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['log_entry']->value) {
$_smarty_tpl->tpl_vars['log_entry']->do_else = false;
?>
                <div class="row justify-content-center">
                    <div class="col-md-2 border rounded text-left">
                        <?php echo $_smarty_tpl->tpl_vars['log_entry']->value['username'];?>

                    </div>
                    <div class="col-md-4 border rounded text-left">
                        <a href="https://tbc.wowhead.com/item=<?php echo $_smarty_tpl->tpl_vars['log_entry']->value['item']['data']['id'];?>
" target="blank""><?php echo $_smarty_tpl->tpl_vars['log_entry']->value['item']['data']['name'];?>
</a>
                        <?php if ($_smarty_tpl->tpl_vars['log_entry']->value['item']['data']['quality'] == "Epic") {?><p style="display: inline; color: #9345ff !important">(Epic)</p>
                        <?php } elseif ($_smarty_tpl->tpl_vars['log_entry']->value['item']['data']['quality'] == "Rare") {?><p style="display: inline; color: #0070dd !important">(Rare)</p>
                        <?php } elseif ($_smarty_tpl->tpl_vars['log_entry']->value['item']['data']['quality'] == "Uncommon") {?><p style="display: inline; color: #0070dd !important">(Uncommon)</p>
                        <?php }?>
                    </div>
                    <div class="col-md-2 border rounded text-left">
                        <?php echo $_smarty_tpl->tpl_vars['log_entry']->value['lootmode'];?>

                    </div>
                    <div class="col-md-2 border rounded text-left">
                        <?php echo $_smarty_tpl->tpl_vars['log_entry']->value['raid_date'];?>

                    </div>
                </div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php }?>
        </div>
    </div>
</div><?php }
}
