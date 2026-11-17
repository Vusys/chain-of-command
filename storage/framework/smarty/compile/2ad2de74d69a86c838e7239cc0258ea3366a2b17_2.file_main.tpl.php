<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/layouts/main.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-17 00:17:26
  from 'file:layouts/main.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6afb9d1613e2e9_54010792',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2ad2de74d69a86c838e7239cc0258ea3366a2b17' => 
    array (
      0 => 'layouts/main.tpl',
      1 => 1794874643,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6afb9d1613e2e9_54010792 (\Smarty\Template $_smarty_tpl) {
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
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_8736333326afb9d16132396_29925892', "title");
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
        <nav class="nav">
            <a href="<?php /*__SLM:15*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'chart'), $_smarty_tpl);?>
">Chart</a>
            <a href="<?php /*__SLM:16*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'people.index'), $_smarty_tpl);?>
">People</a>
        </nav>
    </header>
    <main><?php /*__SLM:19*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_13572456516afb9d1613bf45_88661176', "content");
?>
</main>
    <footer class="debug"><!--identity-map--></footer>
</body>
</html>
<?php }
/* {block "title"} */
class Block_8736333326afb9d16132396_29925892 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views/layouts';
?>
Chain of Command<?php
}
}
/* {/block "title"} */
/* {block "content"} */
class Block_13572456516afb9d1613bf45_88661176 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views/layouts';
}
}
/* {/block "content"} */
}
