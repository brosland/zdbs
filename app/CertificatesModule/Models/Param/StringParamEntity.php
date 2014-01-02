<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_StringParam")
 */
class StringParamEntity extends ParamEntity
{
	/**
	 * @ORM\Column(nullable=TRUE)
	 * @var string
	 */
	private $value;


	/**
	 * @param ParamTypeEntity $paramType
	 * @param CertificateEntity $certificate
	 * @param string $value
	 */
	public function __construct(ParamTypeEntity $paramType, CertificateEntity $certificate, $value)
	{
		parent::__construct($paramType, $certificate);

		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return string
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
		return (string) $this->value;
	}
}