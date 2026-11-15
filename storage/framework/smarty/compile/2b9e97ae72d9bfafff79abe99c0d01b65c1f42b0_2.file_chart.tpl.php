<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-15 13:52:51
  from 'file:/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6af9b933955ba9_89391020',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b9e97ae72d9bfafff79abe99c0d01b65c1f42b0' => 
    array (
      0 => '/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl',
      1 => 1794750770,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6af9b933955ba9_89391020 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php /*__SLM:2*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_10152567046af9b93394ce25_16684131', "title");
?>

<?php /*__SLM:37*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_20282472136af9b93394de18_43505782', "content");
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layouts/main.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_10152567046af9b93394ce25_16684131 extends \Smarty\Runtime\Block
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
class Block_20282472136af9b93394de18_43505782 extends \Smarty\Runtime\Block
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

    <h1>
        Kestrel Labs on <?php /*__SLM:13*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->format('j F Y')), ENT_QUOTES, 'UTF-8');?>

        <small><?php /*__SLM:14*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('headcount')), ENT_QUOTES, 'UTF-8');?>
 people<?php /*__SLM:14*/
if ($_smarty_tpl->getValue('known')) {?> · as we knew it on <?php /*__SLM:14*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('known')->format('j F Y')), ENT_QUOTES, 'UTF-8');
/*__SLM:14*/
}?></small>
    </h1>

    <ul class="tree">
        <?php /*__SLM:18*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('departments'), 'department');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('department')->value) {
$foreach0DoElse = false;
?>
            <?php /*__SLM:19*/
$_smarty_tpl->assign('people', $_smarty_tpl->getValue('byDepartment')->get($_smarty_tpl->getValue('department')->id,array()), false, NULL);?>
            <li class="department depth-<?php /*__SLM:20*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->depth), ENT_QUOTES, 'UTF-8');?>
" data-department="<?php /*__SLM:20*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->name), ENT_QUOTES, 'UTF-8');?>
">
                <h2><?php /*__SLM:21*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->name), ENT_QUOTES, 'UTF-8');?>
 <span class="count"><?php /*__SLM:21*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('people'))), ENT_QUOTES, 'UTF-8');?>
</span></h2>
                <?php /*__SLM:22*/
if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('people'))) {?>
                    <ul class="people">
                        <?php /*__SLM:24*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('people'), 'assignment');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('assignment')->value) {
$foreach1DoElse = false;
?>
                            <li class="person" data-person="<?php /*__SLM:25*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->person->name), ENT_QUOTES, 'UTF-8');?>
">
                                <strong><?php /*__SLM:26*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->person->name), ENT_QUOTES, 'UTF-8');?>
</strong>
                                <span class="title"><?php /*__SLM:27*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->title), ENT_QUOTES, 'UTF-8');?>
</span>
                                <span class="manager"><?php /*__SLM:28*/
if ($_smarty_tpl->getValue('assignment')->manager) {?>reports to <?php /*__SLM:28*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->manager->name), ENT_QUOTES, 'UTF-8');
/*__SLM:28*/
}?></span>
                                <span class="salary"><?php /*__SLM:29*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('currency')($_smarty_tpl->getValue('assignment')->salary,'GBP','en_GB',0)), ENT_QUOTES, 'UTF-8');?>
</span>
                            </li>
                        <?php /*__SLM:31*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                    </ul>
                <?php /*__SLM:33*/
}?>
            </li>
        <?php /*__SLM:35*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    </ul>
<?php
}
}
/* {/block "content"} */
}
