<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Proxy;

use Kdyby;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ProxyAutoloader extends Nette\Loaders\AutoLoader
{

	/**
	 * @var string
	 */
	private $dir;

	/**
	 * @var string
	 */
	private $namespace;



	/**
	 * @param string $proxyDir
	 * @param string $proxyNamespace
	 */
	public function __construct($proxyDir, $proxyNamespace)
	{
		$this->dir = $proxyDir;
		$this->namespace = ltrim($proxyNamespace, "\\");
	}



	/**
	 * @param string $proxyDir
	 * @param string $proxyNamespace
	 * @return ProxyAutoloader
	 */
	public static function create($proxyDir, $proxyNamespace)
	{
		return new static($proxyDir, $proxyNamespace);
	}



	/**
	 * Resolve proxy class name to a filename based on the following pattern.
	 *
	 * 1. Remove Proxy namespace from class name
	 * 2. Remove namespace seperators from remaining class name.
	 * 3. Return PHP filename from proxy-dir with the result from 2.
	 *
	 * @param  string
	 * @return void
	 */
	public function tryLoad($type)
	{
		if (strpos($type, $this->namespace) === 0) {
			$type = str_replace('\\', '', substr($type, strlen($this->namespace) + 1));
			if (file_exists($file = $this->dir . DIRECTORY_SEPARATOR . $type . '.php')) {
				include $file;
			}
		}
	}

}
