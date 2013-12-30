<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Nette\DateTime,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_DateTimeParam")
 */
class DateTimeParamEntity extends ParamEntity
{
	/**
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 * @var DateTime
	 */
	private $value;


	/**
	 * @param ParamTypeEntity $paramType
	 * @param CertificateEntity $certificate
	 * @param DateTime $value
	 */
	public function __construct(ParamTypeEntity $paramType, CertificateEntity $certificate, DateTime $value)
	{
		parent::__construct($paramType, $certificate);

		$this->value = $value;
	}

	/**
	 * @return DateTime
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return DateTime
	 */
	public function setValue(DateTime $value)
	{
		$this->value = $value;
	}
}