<?php
/* Smarty version 3.1.43, created on 2022-05-03 13:55:38
  from 'C:\webs\prestashop\modules\ps_checkout\views\templates\hook\displayProductPriceBlock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6271183a148726_04361316',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '40e40f65c7b2f23a9fd58ade4207f7fb296da94a' => 
    array (
      0 => 'C:\\webs\\prestashop\\modules\\ps_checkout\\views\\templates\\hook\\displayProductPriceBlock.tpl',
      1 => 1649670198,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6271183a148726_04361316 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['totalCartPrice']->value)) && $_smarty_tpl->tpl_vars['payIn4XisProductPageEnabled']->value == true) {?>
  <?php if (!(isset($_smarty_tpl->tpl_vars['content_only']->value)) || $_smarty_tpl->tpl_vars['content_only']->value === 0) {?>
    <div
      data-pp-message
      data-pp-placement="product"
      data-pp-style-layout="text"
      data-pp-style-logo-type="inline"
      data-pp-style-text-color="black"
      data-pp-amount="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['totalCartPrice']->value, ENT_QUOTES, 'UTF-8');?>
"
    ></div>
    <?php echo '<script'; ?>
>
      window.ps_checkoutPayPalSdkInstance
        && window.ps_checkoutPayPalSdkInstance.Messages
        && window.ps_checkoutPayPalSdkInstance.Messages().render('[data-pp-message]');
    <?php echo '</script'; ?>
>
  <?php }
}
}
}
