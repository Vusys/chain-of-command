<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-15 16:44:50
  from 'file:/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6af9e182dfd6d3_32883504',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b9e97ae72d9bfafff79abe99c0d01b65c1f42b0' => 
    array (
      0 => '/home/bryan/code/apps/chain-of-command/resources/views/chart.tpl',
      1 => 1794761065,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6af9e182dfd6d3_32883504 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php /*__SLM:2*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_2295300076af9e182d4e555_17817759', "title");
?>

<?php /*__SLM:41*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_7077149186af9e182d67a23_57675997', "content");
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layouts/main.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_2295300076af9e182d4e555_17817759 extends \Smarty\Runtime\Block
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
class Block_7077149186af9e182d67a23_57675997 extends \Smarty\Runtime\Block
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

    <p class="hint">Drag a department onto another to move it there.</p>
    <p class="error" role="alert" data-chart-message hidden></p>

    <ul class="tree">
        <?php /*__SLM:21*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('departments'), 'department');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('department')->value) {
$foreach0DoElse = false;
?>
            <?php /*__SLM:22*/
$_smarty_tpl->assign('people', $_smarty_tpl->getValue('byDepartment')->get($_smarty_tpl->getValue('department')->id,array()), false, NULL);?>
            <li class="department depth-<?php /*__SLM:23*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->depth), ENT_QUOTES, 'UTF-8');?>
" data-department="<?php /*__SLM:23*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->name), ENT_QUOTES, 'UTF-8');?>
" data-department-id="<?php /*__SLM:23*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->id), ENT_QUOTES, 'UTF-8');?>
" style="margin-left: <?php /*__SLM:23*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->depth*28), ENT_QUOTES, 'UTF-8');?>
px"<?php /*__SLM:23*/
if ($_smarty_tpl->getValue('department')->depth > 0) {?> draggable="true"<?php /*__SLM:23*/
}?>>
                <h2><?php /*__SLM:24*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->name), ENT_QUOTES, 'UTF-8');?>
 <span class="count"><?php /*__SLM:24*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('people'))), ENT_QUOTES, 'UTF-8');?>
</span></h2>
                <?php /*__SLM:25*/
if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('people'))) {?>
                    <ul class="people">
                        <?php /*__SLM:27*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('people'), 'assignment');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('assignment')->value) {
$foreach1DoElse = false;
?>
                            <li class="person" data-person="<?php /*__SLM:28*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->person->name), ENT_QUOTES, 'UTF-8');?>
">
                                <a class="name" href="<?php /*__SLM:29*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'people.show','person'=>$_smarty_tpl->getValue('assignment')->person_id), $_smarty_tpl);?>
"><?php /*__SLM:29*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->person->name), ENT_QUOTES, 'UTF-8');?>
</a>
                                <span class="title"><?php /*__SLM:30*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->title), ENT_QUOTES, 'UTF-8');?>
</span>
                                <span class="manager"><?php /*__SLM:31*/
if ($_smarty_tpl->getValue('assignment')->manager) {?>reports to <?php /*__SLM:31*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->manager->name), ENT_QUOTES, 'UTF-8');
/*__SLM:31*/
}?></span>
                                <span class="salary"><?php /*__SLM:32*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('currency')($_smarty_tpl->getValue('assignment')->salary,'GBP','en_GB',0)), ENT_QUOTES, 'UTF-8');?>
</span>
                            </li>
                        <?php /*__SLM:34*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                    </ul>
                <?php /*__SLM:36*/
}?>
            </li>
        <?php /*__SLM:38*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    </ul>
    <?php echo '<script'; ?>
 src="<?php /*__SLM:40*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('asset')->handle(array('path'=>'js/chart.js'), $_smarty_tpl);?>
" defer><?php echo '</script'; ?>
>
<?php
}
}
/* {/block "content"} */
}
