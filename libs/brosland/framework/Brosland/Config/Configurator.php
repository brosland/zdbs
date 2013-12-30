<?php
namespace Brosland\Config;

use Brosland\Media\Config\Extension as MediaExtension,
	Kdyby;

class Configurator extends \Nette\Configurator
{
	/**
	 * @return \Nette\Config\Compiler
	 */
	protected function createCompiler()
	{
		$compiler = parent::createCompiler();
		
		Kdyby\BootstrapFormRenderer\DI\RendererExtension::register($this);
		Kdyby\Replicator\DI\ReplicatorExtension::register($this);
		Kdyby\Curl\DI\CurlExtension::register($this);
		Kdyby\Console\DI\ConsoleExtension::register($this);
		Kdyby\Events\DI\EventsExtension::register($this);
		Kdyby\Annotations\DI\AnnotationsExtension::register($this);
		Kdyby\Doctrine\DI\OrmExtension::register($this);
		
//		$media = new MediaExtension();
//		$media->defaults['fileRoute'] = 'files/<file>.<ext>';
//		$media->defaults['imageRoute'] = 'images/<format>/<image>.<type>';
//		
//		$compiler->addExtension(MediaExtension::DEFAULT_EXTENSION_NAME, $media);
		
		return $compiler;
	}
}