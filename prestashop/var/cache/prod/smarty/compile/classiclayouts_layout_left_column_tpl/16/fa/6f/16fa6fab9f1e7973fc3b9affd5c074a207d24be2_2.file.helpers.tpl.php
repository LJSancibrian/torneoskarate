<?php
/* Smarty version 3.1.43, created on 2022-04-11 17:25:11
  from 'C:\webs\prestashop\themes\classic\templates\_partials\helpers.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_62544857aae839_57186160',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '16fa6fab9f1e7973fc3b9affd5c074a207d24be2' => 
    array (
      0 => 'C:\\webs\\prestashop\\themes\\classic\\templates\\_partials\\helpers.tpl',
      1 => 1649669518,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62544857aae839_57186160 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->smarty->ext->_tplFunction->registerTplFunctions($_smarty_tpl, array (
  'renderLogo' => 
  array (
    'compiled_filepath' => 'C:\\webs\\prestashop\\var\\cache\\prod\\smarty\\compile\\classiclayouts_layout_left_column_tpl\\16\\fa\\6f\\16fa6fab9f1e7973fc3b9affd5c074a207d24be2_2.file.helpers.tpl.php',
    'uid' => '16fa6fab9f1e7973fc3b9affd5c074a207d24be2',
    'call_name' => 'smarty_template_function_renderLogo_90610589262544857a9a5b5_65152278',
  ),
));
?> 

<?php }
/* smarty_template_function_renderLogo_90610589262544857a9a5b5_65152278 */
if (!function_exists('smarty_template_function_renderLogo_90610589262544857a9a5b5_65152278')) {
function smarty_template_function_renderLogo_90610589262544857a9a5b5_65152278(Smarty_Internal_Template $_smarty_tpl,$params) {
foreach ($params as $key => $value) {
$_smarty_tpl->tpl_vars[$key] = new Smarty_Variable($value, $_smarty_tpl->isRenderingCache);
}
?>

  <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['pages']['index'], ENT_QUOTES, 'UTF-8');?>
">
    <img
      class="logo img-fluid"
      src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['logo_details']['src'], ENT_QUOTES, 'UTF-8');?>
"
      alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
"
      width="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['logo_details']['width'], ENT_QUOTES, 'UTF-8');?>
"
      height="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['logo_details']['height'], ENT_QUOTES, 'UTF-8');?>
">
  </a>
<?php
}}
/*/ smarty_template_function_renderLogo_90610589262544857a9a5b5_65152278 */
}
