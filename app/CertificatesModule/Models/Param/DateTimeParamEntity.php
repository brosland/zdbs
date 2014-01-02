<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	DateTime,
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
	 * @param DateTime|string $value
	 */
	public function __construct(ParamTypeEntity $paramType, CertificateEntity $certificate, $value)
	{
		parent::__construct($paramType, $certificate);
		
		$this->value = is_string($value) ?
			DateTime::createFromFormat(DateTime::W3C, $value) : $value;
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
	
	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->value !== NULL ? $this->value->format(DateTime::W3C) : '';
	}
}