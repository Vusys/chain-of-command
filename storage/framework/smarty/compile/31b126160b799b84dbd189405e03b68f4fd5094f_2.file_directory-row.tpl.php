<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/partials/directory-row.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-17 00:17:26
  from 'file:partials/directory-row.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6afb9d1616be78_46436424',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '31b126160b799b84dbd189405e03b68f4fd5094f' => 
    array (
      0 => 'partials/directory-row.tpl',
      1 => 1794874643,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6afb9d1616be78_46436424 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views/partials';
?><li class="person" data-person="<?php /*__SLM:2*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->person->name), ENT_QUOTES, 'UTF-8');?>
">
    <a class="name" href="<?php /*__SLM:3*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'people.show','person'=>$_smarty_tpl->getValue('assignment')->person_id), $_smarty_tpl);?>
"><?php /*__SLM:3*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('highlight')->handle(array('text'=>$_smarty_tpl->getValue('assignment')->person->name,'term'=>$_smarty_tpl->getValue('q')), $_smarty_tpl);?>
</a>
    <span class="title"><?php /*__SLM:4*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('highlight')->handle(array('text'=>$_smarty_tpl->getValue('assignment')->title,'term'=>$_smarty_tpl->getValue('q')), $_smarty_tpl);?>
</span>
    <span class="manager"><?php /*__SLM:5*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('department_path')->handle(array('department'=>$_smarty_tpl->getValue('assignment')->department), $_smarty_tpl);?>
</span>
    <span class="salary"><?php /*__SLM:6*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('currency')($_smarty_tpl->getValue('assignment')->salary,'GBP','en_GB',0)), ENT_QUOTES, 'UTF-8');?>
</span>
</li>
<?php }
}
