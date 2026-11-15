<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/person.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-15 16:16:33
  from 'file:/home/bryan/code/apps/chain-of-command/resources/views/person.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6af9dae1b09f62_13946861',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6638045affbf403a8426478cb7fd9789f439e8e5' => 
    array (
      0 => '/home/bryan/code/apps/chain-of-command/resources/views/person.tpl',
      1 => 1794759379,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6af9dae1b09f62_13946861 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php /*__SLM:2*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_2742357266af9dae1963627_13882558', "title");
?>

<?php /*__SLM:66*/
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_11472924626af9dae19862e0_42261684', "content");
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layouts/main.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_2742357266af9dae1963627_13882558 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
/*__SLM:2*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('person')->name), ENT_QUOTES, 'UTF-8');
}
}
/* {/block "title"} */
/* {block "content"} */
class Block_11472924626af9dae19862e0_42261684 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views';
?>

    <p><a href="<?php /*__SLM:4*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'chart'), $_smarty_tpl);?>
">&larr; Org chart</a></p>
    <h1><?php /*__SLM:5*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('person')->name), ENT_QUOTES, 'UTF-8');?>
 <small><?php /*__SLM:5*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('person')->email), ENT_QUOTES, 'UTF-8');?>
</small></h1>

    <?php /*__SLM:7*/
if ($_smarty_tpl->getValue('session')->has('status')) {?><p class="notice" role="status"><?php /*__SLM:7*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('session')->status), ENT_QUOTES, 'UTF-8');?>
</p><?php /*__SLM:7*/
}?>
    <?php /*__SLM:8*/
$_block_repeat=true;
if (!$_smarty_tpl->getSmarty()->getBlockHandler('error')) {
throw new \Smarty\Exception('block tag \'error\' not callable or registered');
}

echo $_smarty_tpl->getSmarty()->getBlockHandler('error')->handle(array('field'=>"from"), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
  ob_start();
?><p class="error" role="alert"><?php /*__SLM:8*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('message')), ENT_QUOTES, 'UTF-8');?>
</p><?php /*__SLM:8*/
$_block_repeat=false;
echo $_smarty_tpl->getSmarty()->getBlockHandler('error')->handle(array('field'=>"from"), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
?>

    <h2>Career, as we know it now</h2>
    <table class="timeline">
        <thead><tr><th>From</th><th>Until</th><th>Department</th><th>Reports to</th><th>Title</th><th>Salary</th><th></th></tr></thead>
        <tbody>
        <?php /*__SLM:14*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('timeline'), 'period');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('period')->value) {
$foreach0DoElse = false;
?>
            <tr data-period="<?php /*__SLM:15*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->valid_from->toDateString()), ENT_QUOTES, 'UTF-8');?>
">
                <td><?php /*__SLM:16*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->valid_from->format('j M Y')), ENT_QUOTES, 'UTF-8');?>
</td>
                <td><?php /*__SLM:17*/
if ($_smarty_tpl->getValue('period')->valid_to) {
/*__SLM:17*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->valid_to->format('j M Y')), ENT_QUOTES, 'UTF-8');
/*__SLM:17*/
} else { ?>now<?php /*__SLM:17*/
}?></td>
                <td><?php /*__SLM:18*/
if ($_smarty_tpl->getValue('period')->department) {
/*__SLM:18*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->department->name), ENT_QUOTES, 'UTF-8');
/*__SLM:18*/
}?></td>
                <td><?php /*__SLM:19*/
if ($_smarty_tpl->getValue('period')->manager) {
/*__SLM:19*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->manager->name), ENT_QUOTES, 'UTF-8');
/*__SLM:19*/
} else { ?>—<?php /*__SLM:19*/
}?></td>
                <td><?php /*__SLM:20*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->title), ENT_QUOTES, 'UTF-8');?>
</td>
                <td><?php /*__SLM:21*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('currency')($_smarty_tpl->getValue('period')->salary,'GBP','en_GB',0)), ENT_QUOTES, 'UTF-8');?>
</td>
                <td>
                    <details class="correct">
                        <summary>Correct this period</summary>
                        <form method="post" action="<?php /*__SLM:25*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'people.correct','person'=>$_smarty_tpl->getValue('person')->id), $_smarty_tpl);?>
">
                            <?php /*__SLM:26*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('csrf_field')->handle(array(), $_smarty_tpl);?>

                            <input type="hidden" name="from" value="<?php /*__SLM:27*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->valid_from->toDateString()), ENT_QUOTES, 'UTF-8');?>
">
                            <input type="hidden" name="to" value="<?php /*__SLM:28*/
if ($_smarty_tpl->getValue('period')->valid_to) {
/*__SLM:28*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->valid_to->toDateString()), ENT_QUOTES, 'UTF-8');
/*__SLM:28*/
}?>">
                            <label>Title <input name="title" value="<?php /*__SLM:29*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->title), ENT_QUOTES, 'UTF-8');?>
" required></label>
                            <label>Salary <input name="salary" type="number" min="1" value="<?php /*__SLM:30*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('period')->salary), ENT_QUOTES, 'UTF-8');?>
" required></label>
                            <button type="submit">Save correction</button>
                        </form>
                    </details>
                </td>
            </tr>
        <?php /*__SLM:36*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </tbody>
    </table>

    <h2>Change from a date</h2>
    <form class="change" method="post" action="<?php /*__SLM:41*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'people.change','person'=>$_smarty_tpl->getValue('person')->id), $_smarty_tpl);?>
">
        <?php /*__SLM:42*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('csrf_field')->handle(array(), $_smarty_tpl);?>

        <label>From <input type="date" name="from" min="<?php /*__SLM:43*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('today')->toDateString()), ENT_QUOTES, 'UTF-8');?>
" value="<?php /*__SLM:43*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('old')->handle(array('field'=>'from','default'=>$_smarty_tpl->getValue('today')->toDateString()), $_smarty_tpl);?>
" required></label>
        <label>New title <input name="title" value="<?php /*__SLM:44*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('old')->handle(array('field'=>'title'), $_smarty_tpl);?>
" required></label>
        <label>New salary <input name="salary" type="number" min="1" value="<?php /*__SLM:45*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('old')->handle(array('field'=>'salary'), $_smarty_tpl);?>
" required></label>
        <button type="submit">Record the change</button>
    </form>

    <?php /*__SLM:49*/
if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('superseded'))) {?>
        <h2>Beliefs we have since corrected</h2>
        <table class="timeline superseded">
            <thead><tr><th>Period from</th><th>Title</th><th>Salary</th><th>Believed from</th><th>Until</th></tr></thead>
            <tbody>
            <?php /*__SLM:54*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('superseded'), 'row');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('row')->value) {
$foreach1DoElse = false;
?>
                <tr>
                    <td><?php /*__SLM:56*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('row')->valid_from->format('j M Y')), ENT_QUOTES, 'UTF-8');?>
</td>
                    <td><?php /*__SLM:57*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('row')->title), ENT_QUOTES, 'UTF-8');?>
</td>
                    <td><?php /*__SLM:58*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('currency')($_smarty_tpl->getValue('row')->salary,'GBP','en_GB',0)), ENT_QUOTES, 'UTF-8');?>
</td>
                    <td><?php /*__SLM:59*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('row')->recorded_from->format('j M Y')), ENT_QUOTES, 'UTF-8');?>
</td>
                    <td><?php /*__SLM:60*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('row')->recorded_to->format('j M Y')), ENT_QUOTES, 'UTF-8');?>
</td>
                </tr>
            <?php /*__SLM:62*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </tbody>
        </table>
    <?php /*__SLM:65*/
}
}
}
/* {/block "content"} */
}
