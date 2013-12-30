<?php //netteCache[01]000367a:2:{s:4:"time";s:21:"0.53387900 1387868116";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:47:"E:\Work\htdocs\zdbs\app\templates\@layout.latte";i:2;i:1387868006;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: E:\Work\htdocs\zdbs\app\templates\@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ho8je3vinr')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lbffeb74a023_head')) { function _lbffeb74a023_head($_l, $_args) { extract($_args)
;
}}

//
// block body
//
if (!function_exists($_l->blocks['body'][] = '_lb8f1e4d70d9_body')) { function _lb8f1e4d70d9_body($_l, $_args) { extract($_args)
?>		<div id="page-wrap">
			<div id="page">
				<div id="header-wrap">
					<div id="header">
						<div id="links">
							<ul class="ui-helper-clearfix">
<?php if ($user->isLoggedIn()): if ($user->isAllowed('administration')): ?>								<li><a href="<?php echo htmlSpecialChars($_control->link(":Admin:Dashboard:")) ?>
">Administrácia</a></li>
<?php endif ?>
								<li><a href="<?php echo htmlSpecialChars($_control->link(":Front:User:edit")) ?>
">Profil</a></li>
								<li><a href="<?php echo htmlSpecialChars($_control->link(":Front:Sign:out")) ?>
">Odhlásiť sa</a>
<?php else: ?>
								<li><a href="<?php echo htmlSpecialChars($_control->link(":Front:Sign:in")) ?>
">Prihlásiť</a></li>
<?php endif ?>
							</ul>
						</div>
						<div id="logo">
							<h1><a class="notranslate" href="<?php echo htmlSpecialChars($_control->link(":Front:Homepage:")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($control->getPageParam('name'), ENT_NOQUOTES) ?></a></h1>
						</div>
					</div>
				</div>
				<div id="menu-wrap">
					<div id="menu"><?php call_user_func(reset($_l->blocks['menu']), $_l, get_defined_vars())  ?></div>
				</div>
				<div id="content-wrap">
					<div id="content-wrap2">
						<div id="content-wrap-inner" class="ui-helper-clearfix">
							<div id="content" class="ui-helper-clearfix">
<div id="<?php echo $_control->getSnippetId('content') ?>"><?php call_user_func(reset($_l->blocks['_content']), $_l, $template->getParameters()) ?>
</div>							</div>
						</div>
					</div>
				</div>
				<div id="footer-wrap">
					<div id="footer">
						<b class="notranslate">&copy;2013 <?php echo Nette\Templating\Helpers::escapeHtml($control->getPageParam('name'), ENT_NOQUOTES) ?></b>
					</div>
				</div>
			</div>
		</div>
<?php
}}

//
// block menu
//
if (!function_exists($_l->blocks['menu'][] = '_lb51e17f07e6_menu')) { function _lb51e17f07e6_menu($_l, $_args) { extract($_args)
;
}}

//
// block _content
//
if (!function_exists($_l->blocks['_content'][] = '_lb8bb3762ea0__content')) { function _lb8bb3762ea0__content($_l, $_args) { extract($_args); $_control->validateControl('content')
 ?>
<div id="<?php echo $_control->getSnippetId('flashMessages') ?>"><?php call_user_func(reset($_l->blocks['_flashMessages']), $_l, $template->getParameters()) ?>
</div><?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ;
}}

//
// block _flashMessages
//
if (!function_exists($_l->blocks['_flashMessages'][] = '_lbe476322f0b__flashMessages')) { function _lbe476322f0b__flashMessages($_l, $_args) { extract($_args); $_control->validateControl('flashMessages')
;$iterations = 0; foreach ($flashes as $flash): ?>									<div class="flash message <?php echo htmlSpecialChars($flash->type) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ;
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php if (isset($description)): ?>		<meta name="description" content="<?php echo htmlSpecialChars($description) ?>" />
<?php endif ;if (isset($keywords)): ?>		<meta name="keywords" content="<?php echo htmlSpecialChars($keywords) ?>" />
<?php endif ?>
		<meta name="robots" content="noindex" />
		<meta name="googlebot" content="noindex" />
		
		<!-- jQuery -->
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/smoothness/jquery-ui.css" media="all" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>
		
		<!-- Google -->
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Oswald&subset=latin,latin-ext" />
		<script type="text/javascript" src="<?php echo htmlSpecialChars($basePath) ?>/js/google.translate.js"></script>
		
		<!-- fancyBox -->
		<link rel="stylesheet" href="<?php echo htmlSpecialChars($basePath) ?>/plugins/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
		<script type="text/javascript" src="<?php echo htmlSpecialChars($basePath) ?>/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
		
		<!-- Nette -->
		<script type="text/javascript" src="<?php echo htmlSpecialChars($basePath) ?>/js/netteForms.js"></script>
		<script type="text/javascript" src="<?php echo htmlSpecialChars($basePath) ?>/js/nette.ajax.js"></script>
		
		<!-- brosland -->
		<script type="text/javascript" src="<?php echo htmlSpecialChars($basePath) ?>/js/jquery.brosland.js"></script>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo htmlSpecialChars($basePath) ?>/css/screen.css?v=1.0" />
		
		<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

		<title><?php if (isset($title)): echo Nette\Templating\Helpers::escapeHtml($title, ENT_NOQUOTES) ?>
 | <?php endif ;echo Nette\Templating\Helpers::escapeHtml($control->getPageParam('name'), ENT_NOQUOTES) ?></title>
	</head>
	<body>
<?php call_user_func(reset($_l->blocks['body']), $_l, get_defined_vars())  ?>	</body>
</html>