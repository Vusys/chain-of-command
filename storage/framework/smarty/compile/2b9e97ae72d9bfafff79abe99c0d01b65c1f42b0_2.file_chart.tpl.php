<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-15 17:25:50
  from 'file:/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6af9eb1e5a0539_35914904',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b9e97ae72d9bfafff79abe99c0d01b65c1f42b0' => 
    array (
      0 => '/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl',
      1 => 1794763524,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/chart-body.tpl' => 1,
  ),
))) {
function content_6af9eb1e5a0539_35914904 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php /*__SLM:2*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_10330295756af9eb1e5918b1_06437548', "title");
?>

<?php /*__SLM:23*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_10296871626af9eb1e596521_76208108', "content");
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layouts/main.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_10330295756af9eb1e5918b1_06437548 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
?>
Org chart on <?php /*__SLM:2*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->format('j M Y')), ENT_QUOTES, 'UTF-8');
}
}
/* {/block "title"} */
/* {block "content"} */
class Block_10296871626af9eb1e596521_76208108 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
?>

    <form class="as-of" method="get" action="<?php /*__SLM:4*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'chart'), $_smarty_tpl);?>
">
        <a class="button" href="<?php /*__SLM:5*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'chart','on'=>$_smarty_tpl->getValue('previous')->toDateString()), $_smarty_tpl);?>
" rel="prev">&larr; <?php /*__SLM:5*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('previous')->format('M Y')), ENT_QUOTES, 'UTF-8');?>
</a>
        <label>On <input type="date" name="on" value="<?php /*__SLM:6*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->toDateString()), ENT_QUOTES, 'UTF-8');?>
"></label>
        <label>As we knew it on <input type="date" name="known" value="<?php /*__SLM:7*/
if ($_smarty_tpl->getValue('known')) {
/*__SLM:7*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('known')->toDateString()), ENT_QUOTES, 'UTF-8');
/*__SLM:7*/
}?>"></label>
        <button type="submit">Show</button>
        <a class="button" href="<?php /*__SLM:9*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'chart','on'=>$_smarty_tpl->getValue('next')->toDateString()), $_smarty_tpl);?>
" rel="next"><?php /*__SLM:9*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('next')->format('M Y')), ENT_QUOTES, 'UTF-8');?>
 &rarr;</a>
    </form>

    <label class="slider">
        <span>Scrub through time</span>
        <input type="range" data-as-of min="0" max="<?php /*__SLM:14*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('months'))-1), ENT_QUOTES, 'UTF-8');?>
" value="<?php /*__SLM:14*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('monthIndex')), ENT_QUOTES, 'UTF-8');?>
" data-months="<?php /*__SLM:14*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('monthList')), ENT_QUOTES, 'UTF-8');?>
">
        <output data-as-of-label><?php /*__SLM:15*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->format('M Y')), ENT_QUOTES, 'UTF-8');?>
</output>
    </label>

    <p class="hint">Drag a department onto another to move it there.</p>
    <p class="error" role="alert" data-chart-message hidden></p>

    <div data-chart-body><?php /*__SLM:21*/
$_smarty_tpl->renderSubTemplate("file:partials/chart-body.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?></div>
    <?php echo '<script'; ?>
 src="<?php /*__SLM:22*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('asset')->handle(array('path'=>'js/chart.js'), $_smarty_tpl);?>
" defer><?php echo '</script'; ?>
>
<?php
}
}
/* {/block "content"} */
}
