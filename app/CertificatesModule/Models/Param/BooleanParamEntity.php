<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
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
	 * @return string
	 */
	public function __toString()
	{
		return $this->value === TRUE ? '1' : '0';
	}
}