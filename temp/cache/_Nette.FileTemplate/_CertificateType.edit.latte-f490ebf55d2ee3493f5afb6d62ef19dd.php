<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.81335700 1387889395";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:91:"E:\Work\htdocs\zdbs\app\CertificatesModule\AdminModule\templates\CertificateType\edit.latte";i:2;i:1387889350;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: E:\Work\htdocs\zdbs\app\CertificatesModule\AdminModule\templates\CertificateType\edit.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'gvkrm1u88c')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb7dd1128227_content')) { function _lb7dd1128227_content($_l, $_args) { extract($_args)
?><h2><?php if ($control->action === 'add'): ?>Vytvorenie<?php else: ?>Editácia<?php endif ?> typu certifikátu</h2>

<div id="<?php echo $_control->getSnippetId('certificateTypeForm') ?>"><?php call_user_func(reset($_l->blocks['_certificateTypeForm']), $_l, $template->getParameters()) ?>
</div><?php
}}

//
// block _certificateTypeForm
//
if (!function_exists($_l->blocks['_certificateTypeForm'][] = '_lb5074c78aad__certificateTypeForm')) { function _lb5074c78aad__certificateTypeForm($_l, $_args) { extract($_args); $_control->validateControl('certificateTypeForm')
;$form = $control['certificateTypeForm'] ;$form = $__form = $_form = Kdyby\BootstrapFormRenderer\Latte\FormMacros::renderFormPart($form, array(), get_defined_vars()) ;$__form->render(is_object('Certifikát') ? 'Certifikát' : $__form->getGroup('Certifikát')) ?>
		<fieldset id="param-types">
			<legend>Parametre certifikátu</legend>
<?php $iterations = 0; foreach ($form['paramTypes']->containers as $id => $paramType): ?>			<div class="param-type ui-helper-clearfix">
				<div class="column dragdrop-drag-handle">
					a
				</div>
				<div class="column">
<?php $__form->render($__form["paramTypes-$id-label"]) ;$__form->render($__form["paramTypes-$id-paramTypeId"]) ?>
				</div>
				<div class="column">
<?php $__form->render($__form["paramTypes-$id-name"]) ?>
					<div class="required-field"><?php $__form->render($__form["paramTypes-$id-required"]) ?></div>
				</div>
				<div class="column remove-button">
<?php $_input = (is_object("paramTypes-$id-remove") ? "paramTypes-$id-remove" : $_form["paramTypes-$id-remove"]); echo $_input->getControl()->addAttributes(array()) ?>
				</div>
			</div>
<?php $iterations++; endforeach ?>
		</fieldset>
<?php $form = $__form = $_form = Kdyby\BootstrapFormRenderer\Latte\FormMacros::renderFormPart("buttons", array(), get_defined_vars()) ;Nette\Latte\Macros\FormMacros::renderFormEnd($__form) ;
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
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 