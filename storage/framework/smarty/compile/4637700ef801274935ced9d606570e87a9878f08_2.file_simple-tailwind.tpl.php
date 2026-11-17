<?php /*__SLF:/home/bryan/code/apps/chain-of-command/vendor/vusys/laravel-smarty/resources/views/pagination/simple-tailwind.tpl*/ ?><?php
/* Smarty version 5.8.5, created on 2026-11-17 00:18:13
  from 'file:/home/bryan/code/apps/chain-of-command/vendor/vusys/laravel-smarty/resources/views/pagination/simple-tailwind.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.5',
  'unifunc' => 'content_6afb9d4539d306_27080819',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4637700ef801274935ced9d606570e87a9878f08' => 
    array (
      0 => '/home/bryan/code/apps/chain-of-command/vendor/vusys/laravel-smarty/resources/views/pagination/simple-tailwind.tpl',
      1 => 1783463921,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6afb9d4539d306_27080819 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/bryan/code/apps/chain-of-command/vendor/vusys/laravel-smarty/resources/views/pagination';
/*__SLM:1*/
if ($_smarty_tpl->getValue('paginator')->hasPages()) {?>
    <nav role="navigation" aria-label="<?php /*__SLM:2*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('lang')->handle(array('key'=>"Pagination Navigation"), $_smarty_tpl);?>
" class="flex gap-2 items-center justify-between">

        <?php /*__SLM:4*/
if ($_smarty_tpl->getValue('paginator')->onFirstPage()) {?>
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                <?php /*__SLM:6*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('lang')->handle(array('key'=>"pagination.previous"), $_smarty_tpl);?>

            </span>
        <?php /*__SLM:8*/
} else { ?>
            <a href="<?php /*__SLM:9*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('paginator')->previousPageUrl()), ENT_QUOTES, 'UTF-8');?>
" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-200">
                <?php /*__SLM:10*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('lang')->handle(array('key'=>"pagination.previous"), $_smarty_tpl);?>

            </a>
        <?php /*__SLM:12*/
}?>

        <?php /*__SLM:14*/
if ($_smarty_tpl->getValue('paginator')->hasMorePages()) {?>
            <a href="<?php /*__SLM:15*/
echo htmlspecialchars((string) ($_smarty_tpl->getValue('paginator')->nextPageUrl()), ENT_QUOTES, 'UTF-8');?>
" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-200">
                <?php /*__SLM:16*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('lang')->handle(array('key'=>"pagination.next"), $_smarty_tpl);?>

            </a>
        <?php /*__SLM:18*/
} else { ?>
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                <?php /*__SLM:20*/
echo $_smarty_tpl->getSmarty()->getFunctionHandler('lang')->handle(array('key'=>"pagination.next"), $_smarty_tpl);?>

            </span>
        <?php /*__SLM:22*/
}?>

    </nav>
<?php /*__SLM:25*/
}
}
}
