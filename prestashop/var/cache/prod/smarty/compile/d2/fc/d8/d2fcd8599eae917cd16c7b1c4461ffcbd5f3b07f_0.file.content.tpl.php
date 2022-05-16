<?php
/* Smarty version 3.1.43, created on 2022-05-03 17:19:01
  from 'C:\webs\prestashop\admin563fv00tg\themes\default\template\content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_627147e58850a2_15256425',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd2fcd8599eae917cd16c7b1c4461ffcbd5f3b07f' => 
    array (
      0 => 'C:\\webs\\prestashop\\admin563fv00tg\\themes\\default\\template\\content.tpl',
      1 => 1649669503,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_627147e58850a2_15256425 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
