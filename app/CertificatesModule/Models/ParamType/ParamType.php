<?php
namespace CertificatesModule\Models\ParamType;

abstract class ParamType extends \Nette\Object
{
	const
		BOOLEAN = 0,
		INTEGER = 1,
		DOUBLE = 2,
		STRING = 3,
		TEXT = 4,
		DATETIME = 5;

	/**
	 * @var array
	 */
	private static $VALUES = array(
		self::BOOLEAN => 'boolean',
		self::INTEGER => 'integer',
		self::DOUBLE => 'double',
		self::STRING => 'string',
		self::TEXT => 'text',
		self::DATETIME => 'datetime'
	);


	/**
	 * @return array
	 */
	public static function getValues()
	{
		return self::$VALUES;
	}

	/**
	 * @param int $paramTypeId
	 * @return string
	 */
	public static function getLabel($paramTypeId)
	{
		if(isset(self::$VALUES[$paramTypeId]))
		{
			return self::$VALUES[$paramTypeId];
		}
		
		throw new \Nette\InvalidArgumentException(
			sprintf('Param type with id %d does not exists.', $paramTypeId));
	}
}