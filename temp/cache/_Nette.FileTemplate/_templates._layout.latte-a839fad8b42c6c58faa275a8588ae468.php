<?php //netteCache[01]000398a:2:{s:4:"time";s:21:"0.45587400 1387868116";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:78:"E:\Work\htdocs\zdbs\app\CertificatesModule\AdminModule\templates\@layout.latte";i:2;i:1387868082;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: E:\Work\htdocs\zdbs\app\CertificatesModule\AdminModule\templates\@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '0liug6kzfo')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lb7ba0cd2e5b_head')) { function _lb7ba0cd2e5b_head($_l, $_args) { extract($_args)
?><link rel="stylesheet" type="text/css" media="all" href="<?php echo htmlSpecialChars($basePath) ?>/css/certificates/admin/screen.css" />
<?php
}}

//
// block menu
//
if (!function_exists($_l->blocks['menu'][] = '_lb81bcd56d1c_menu')) { function _lb81bcd56d1c_menu($_l, $_args) { extract($_args)
?><ul>
	<li <?php if ($presenterName == 'CertificateType'): ?>class="current"<?php endif ?>
><a href="<?php echo htmlSpecialChars($_control->link("CertificateType:add")) ?>
">Typy certifikátov</a></li>
</ul>
<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = APP_DIR.'/templates/@layout.latte'; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
 $presenterName = $control->getPresenterName() ?>

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

<?php call_user_func(reset($_l->blocks['menu']), $_l, get_defined_vars()) ; 