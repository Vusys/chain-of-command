<?php /*__SLF:/home/bryan/code/apps/chain-of-command/resources/views/partials/chart-body.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-15 17:26:11
  from 'file:partials/chart-body.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6af9eb33dca229_32389481',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b78a92f916b443fd751618bce1e7877db2f3f4d5' => 
    array (
      0 => 'partials/chart-body.tpl',
      1 => 1794763511,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6af9eb33dca229_32389481 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/resources/views/partials';
?><h1>
    Kestrel Labs on <?php /*__SLM:3*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('on')->format('j F Y')), ENT_QUOTES, 'UTF-8');?>

    <small><?php /*__SLM:4*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('headcount')), ENT_QUOTES, 'UTF-8');?>
 people<?php /*__SLM:4*/
if ($_smarty_tpl->getValue('known')) {?> · as we knew it on <?php /*__SLM:4*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('known')->format('j F Y')), ENT_QUOTES, 'UTF-8');
/*__SLM:4*/
}?></small>
</h1>

<ul class="tree">
    <?php /*__SLM:8*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('departments'), 'department');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('department')->value) {
$foreach0DoElse = false;
?>
        <?php /*__SLM:9*/
$_smarty_tpl->assign('people', $_smarty_tpl->getValue('byDepartment')->get($_smarty_tpl->getValue('department')->id,array()), false, NULL);?>
        <li class="department depth-<?php /*__SLM:10*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->depth), ENT_QUOTES, 'UTF-8');?>
" data-department="<?php /*__SLM:10*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->name), ENT_QUOTES, 'UTF-8');?>
" data-department-id="<?php /*__SLM:10*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->id), ENT_QUOTES, 'UTF-8');?>
" style="margin-left: <?php /*__SLM:10*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->depth*28), ENT_QUOTES, 'UTF-8');?>
px"<?php /*__SLM:10*/
if ($_smarty_tpl->getValue('department')->depth > 0) {?> draggable="true"<?php /*__SLM:10*/
}?>>
            <h2><?php /*__SLM:11*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('department')->name), ENT_QUOTES, 'UTF-8');?>
 <span class="count"><?php /*__SLM:11*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('people'))), ENT_QUOTES, 'UTF-8');?>
</span></h2>
            <?php /*__SLM:12*/
if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('people'))) {?>
                <ul class="people">
                    <?php /*__SLM:14*/
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('people'), 'assignment');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('assignment')->value) {
$foreach1DoElse = false;
?>
                        <li class="person" data-person="<?php /*__SLM:15*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->person->name), ENT_QUOTES, 'UTF-8');?>
">
                            <a class="name" href="<?php /*__SLM:16*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('route')->handle(array('name'=>'people.show','person'=>$_smarty_tpl->getValue('assignment')->person_id), $_smarty_tpl);?>
"><?php /*__SLM:16*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->person->name), ENT_QUOTES, 'UTF-8');?>
</a>
                            <span class="title"><?php /*__SLM:17*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->title), ENT_QUOTES, 'UTF-8');?>
</span>
                            <span class="manager"><?php /*__SLM:18*/
if ($_smarty_tpl->getValue('assignment')->manager) {?>reports to <?php /*__SLM:18*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('assignment')->manager->name), ENT_QUOTES, 'UTF-8');
/*__SLM:18*/
}?></span>
                            <span class="salary"><?php /*__SLM:19*/
echo htmlspecialchars((string) ($_smarty_tpl->getSmarty()->getModifierCallback('currency')($_smarty_tpl->getValue('assignment')->salary,'GBP','en_GB',0)), ENT_QUOTES, 'UTF-8');?>
</span>
                        </li>
                    <?php /*__SLM:21*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                </ul>
            <?php /*__SLM:23*/
}?>
        </li>
    <?php /*__SLM:25*/
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
</ul>
<?php }
}
