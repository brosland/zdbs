<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamType,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_BooleanParam")
 */
class BooleanParamEntity extends ParamEntity
{
	/**
	 * @ORM\Column(type="boolean", nullable=TRUE)
	 * @var bool
	 */
	private $value;


	/**
	 * @param ParamTypeEntity $paramType
	 * @param CertificateEntity $certificate
	 * @param bool $value
	 */
	public function __construct(ParamTypeEntity $paramType, CertificateEntity $certificate, $value)
	{
		parent::__construct($paramType, $certificate);

		$this->value = $value;
	}

	/**
	 * @return bool
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return bool
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}
	
	/**
	 * @param int $paramTypeId
	 * @return mixed
	 */
	public function convertValueTo($paramTypeId)
	{
		switch ($paramTypeId)
		{
			case ParamType::INTEGER:
				return (int) $this->value;
			case ParamType::DOUBLE:
				return (double) $this->value;
			case ParamType::STRING:
			case ParamType::TEXT:
				return (string) $this->value;
		}
		
		throw new \Nette\InvalidArgumentException(
			sprintf('Can not convert boolean to %s.', ParamType::getLabel($paramTypeId))
		);
	}
}