<?php
/* Smarty version 4.0.0, created on 2022-01-13 10:24:27
  from '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk-addmember.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.0.0',
  'unifunc' => 'content_61e0442b3e8c31_94722588',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a7f076d98340b017cfbef6b44dab8d777e91b17b' => 
    array (
      0 => '/home/dh_m6zq7r/mutiny-guild.com/templates/ksk-addmember.tpl',
      1 => 1642087386,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61e0442b3e8c31_94722588 (Smarty_Internal_Template $_smarty_tpl) {
?><div style="padding-top: 60px;">
    <div class="row about-title" style="display: block;">
        <div class="col">
            <strong>Add KSK Member</strong> 
        </div>
    </div>
    <div class="row about-section opacity-90">
        <div class="col">
            Use this form to add a new member to the KSK list.  New members are added to the bottom of the list by default.
        </div>
    </div>
    <div class="row about-section" style="padding: 10px;">
        <div class="col">
            <form action="index.php" method="post" id="frmAddMember>
                <div class="form-group">
                    <label for="inpNewMember">New Member Name</label>
                    <input class="form-control" name="newmember" id="textareaNewList" rows="3" placeholder="Enter name here"></input>
                </div>
                <!--<div class="form-group">
                    <input type="checkbox" class="form-check-input" name="insertrandomly" id="chkOverwriteList" onclick="toggleSubmit();">
                    <label class="form-check-label" for="chkinsertrandom">Insert randomly</label>
                </div>-->
                <input type="hidden" name="do" value="addmember" />
                <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div><?php }
}
