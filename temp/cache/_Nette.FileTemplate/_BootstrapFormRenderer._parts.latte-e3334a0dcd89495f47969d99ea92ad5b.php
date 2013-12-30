<?php //netteCache[01]000429a:2:{s:4:"time";s:21:"0.60962900 1387745684";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:108:"E:\Work\htdocs\zdbs\libs\composer\kdyby\bootstrap-form-renderer\src\Kdyby\BootstrapFormRenderer\@parts.latte";i:2;i:1386942943;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: E:\Work\htdocs\zdbs\libs\composer\kdyby\bootstrap-form-renderer\src\Kdyby\BootstrapFormRenderer\@parts.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'yajp5po2c7')
;
// prolog Nette\Latte\Macros\UIMacros

// snippets support
if (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
Nette\Latte\Macros\CoreMacros::includeTemplate("@form.latte", get_defined_vars(), $_l->templates['yajp5po2c7'])->render() ?>

<?php if ($mode === "errors"): Nette\Latte\Macros\UIMacros::callBlock($_l, 'errors', array('renderer' => $renderer) + $template->getParameters()) ?>

<?php elseif ($mode === "body"): Nette\Latte\Macros\UIMacros::callBlock($_l, 'body', array('renderer' => $renderer) + $template->getParameters()) ?>

<?php elseif ($mode === "controls"): Nette\Latte\Macros\UIMacros::callBlock($_l, 'controls', array('controls' => $renderer->findControls(NULL, FALSE)) + $template->getParameters()) ?>

<?php elseif ($mode === "buttons"): Nette\Latte\Macros\UIMacros::callBlock($_l, 'controls', array('controls' => $renderer->findControls(NULL, TRUE)) + $template->getParameters()) ?>

<?php elseif ($mode instanceof \Nette\Forms\Container): Nette\Latte\Macros\UIMacros::callBlock($_l, 'controls', array('renderer' => $renderer, 'controls' => $renderer->findControls($mode)) + $template->getParameters()) ?>

<?php elseif ($mode instanceof \Nette\Forms\ControlGroup): if ($mode->controls): Nette\Latte\Macros\UIMacros::callBlock($_l, 'group', array('renderer' => $renderer, 'group' => $renderer->processGroup($mode)) + $template->getParameters()) ;endif ?>

<?php elseif ($mode instanceof \Nette\Forms\IControl): Nette\Latte\Macros\UIMacros::callBlock($_l, 'control', array('renderer' => $renderer, 'control' => $mode) + $template->getParameters()) ?>

<?php endif ;