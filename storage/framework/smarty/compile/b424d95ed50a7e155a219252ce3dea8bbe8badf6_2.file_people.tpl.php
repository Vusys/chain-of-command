<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/people.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-17 01:44:25
  from 'file:/home/bryan/code/apps/chain-of-command/resources/views/people.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6afbb179d7e5d2_11599251',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b424d95ed50a7e155a219252ce3dea8bbe8badf6' => 
    array (
      0 => '/home/bryan/code/apps/chain-of-command/resources/views/people.tpl',
      1 => 1794879857,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/directory-row.tpl' => 1,
  ),
))) {
function content_6afbb179d7e5d2_11599251 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php /*__SLM:2*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_17868697536afbb179cf9a02_32904092', "title");
?>

<?php /*__SLM:34*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_12877634946afbb179d14243_40979842', "content");
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layouts/main.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_17868697536afbb179cf9a02_32904092 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
?>
People on <?php /*__SLM:2*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->format('j M Y')), ENT_QUOTES, 'UTF-8');
}
}
/* {/block "title"} */
/* {block "content"} */
class Block_12877634946afbb179d14243_40979842 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
?>

    <h1>
        People
        <small><?php /*__SLM:6*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('results')->total()), ENT_QUOTES, 'UTF-8');?>
 in post on <?php /*__SLM:6*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->format('j F Y')), ENT_QUOTES, 'UTF-8');
/*__SLM:6*/
if ($_smarty_tpl->getValue('department')) {?> · <?php /*__SLM:6*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('department_path')->handle(array('department'=>$_smarty_tpl->getValue('department')), $_smarty_tpl);
/*__SLM:6*/
}?></small>
    </h1>

    <form class="as-of" method="get" action="<?php /*__SLM:9*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'people.index'), $_smarty_tpl);?>
" role="search">
        <label>Search <input type="search" name="q" value="<?php /*__SLM:10*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('q')), ENT_QUOTES, 'UTF-8');?>
" placeholder="Name or title"></label>
        <label>Department
            <select name="department">
                <option value="">Everyone</option>
                <?php /*__SLM:14*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('departments'), 'option');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('option')->value) {
$foreach0DoElse = false;
?>
                    <option value="<?php /*__SLM:15*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('option')->id), ENT_QUOTES, 'UTF-8');?>
"<?php /*__SLM:15*/
if ($_smarty_tpl->getValue('department') && $_smarty_tpl->getValue('department')->id == $_smarty_tpl->getValue('option')->id) {?> selected<?php /*__SLM:15*/
}?>><?php /*__SLM:15*/
echo htmlspecialchars((string) (str_repeat((string) '— ', (int) $_smarty_tpl->getValue('option')->depth)), ENT_QUOTES, 'UTF-8');
/*__SLM:15*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('option')->name), ENT_QUOTES, 'UTF-8');?>
</option>
                <?php /*__SLM:16*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </select>
        </label>
        <label>On <input type="date" name="on" value="<?php /*__SLM:19*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->toDateString()), ENT_QUOTES, 'UTF-8');?>
"></label>
        <button type="submit">Find</button>
    </form>

    <?php /*__SLM:23*/
if ($_smarty_tpl->getValue('results')->isEmpty()) {?>
        <p class="notice" data-no-results>Nobody matches<?php /*__SLM:24*/
if ($_smarty_tpl->getValue('q')) {?> “<?php /*__SLM:24*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('q')), ENT_QUOTES, 'UTF-8');?>
”<?php /*__SLM:24*/
}?>.</p>
    <?php /*__SLM:25*/
} else { ?>
        <ul class="directory">
            <?php /*__SLM:27*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('results'), 'assignment');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('assignment')->value) {
$foreach1DoElse = false;
?>
                <?php /*__SLM:28*/
$_smarty_tpl->renderSubTemplate("file:partials/directory-row.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
            <?php /*__SLM:29*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </ul>
                <?php /*__SLM:32*/
echo $_smarty_tpl->getValue('results')->links('pagination::simple-tailwind');?>

    <?php /*__SLM:33*/
}
}
}
/* {/block "content"} */
}
