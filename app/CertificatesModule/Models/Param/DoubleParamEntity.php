<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_DoubleParam")
 */
class DoubleParamEntity extends ParamEntity
{
	/**
	 * @ORM\Column(type="decimal", precision=10, scale=6, nullable=TRUE)
	 * @var double
	 */
	private $value;


	/**
	 * @param ParamTypeEntity $paramType
	 * @param CertificateEntity $certificate
	 * @param double $value
	 */
	public function __construct(ParamTypeEntity $paramType, CertificateEntity $certificate, $value)
	{
		parent::__construct($paramType, $certificate);

		$this->value = $value;
	}

	/**
	 * @return double
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return double
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}
}