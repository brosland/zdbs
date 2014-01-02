<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_IntegerParam")
 */
class IntegerParamEntity extends ParamEntity
{
	/**
	 * @ORM\Column(type="integer", nullable=TRUE)
	 * @var int
	 */
	private $value;


	/**
	 * @param ParamTypeEntity $paramType
	 * @param CertificateEntity $certificate
	 * @param int|string $value
	 */
	public function __construct(ParamTypeEntity $paramType, CertificateEntity $certificate, $value)
	{
		parent::__construct($paramType, $certificate);

		$this->value = (int) $value;
	}

	/**
	 * @return int
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return int
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