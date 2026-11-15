<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/layouts/main.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-15 13:52:31
  from 'file:layouts/main.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6af9b91fcd02c2_43506428',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2ad2de74d69a86c838e7239cc0258ea3366a2b17' => 
    array (
      0 => 'layouts/main.tpl',
      1 => 1794750725,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6af9b91fcd02c2_43506428 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views/layouts';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php /*__SLM:6*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('csrf_token')->handle(array(), $_smarty_tpl);?>
">
    <title><?php /*__SLM:7*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_17043628236af9b91fccf385_98628853', "title");
?>
</title>
    <link rel="stylesheet" href="<?php /*__SLM:8*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('asset')->handle(array('path'=>'css/app.css'), $_smarty_tpl);?>
">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="<?php /*__SLM:12*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'chart'), $_smarty_tpl);?>
">Chain of Command</a>
        <span class="company">Kestrel Labs</span>
    </header>
    <main><?php /*__SLM:15*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_13591050916af9b91fccff05_21600005', "content");
?>
</main>
    <footer class="debug"><!--identity-map--></footer>
</body>
</html>
<?php }
/* {block "title"} */
class Block_17043628236af9b91fccf385_98628853 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views/layouts';
?>
Chain of Command<?php
}
}
/* {/block "title"} */
/* {block "content"} */
class Block_13591050916af9b91fccff05_21600005 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views/layouts';
}
}
/* {/block "content"} */
}
