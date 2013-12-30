<?php
namespace CertificatesModule\Models\Param;

use CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_Param")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="integer")
 * @ORM\DiscriminatorMap({
 * 	"0"="CertificatesModule\Models\Param\BooleanParamEntity",
 * 	"1"="CertificatesModule\Models\Param\IntegerParamEntity",
 * 	"2"="CertificatesModule\Models\Param\DoubleParamEntity",
 * 	"3"="CertificatesModule\Models\Param\StringParamEntity",
 * 	"4"="CertificatesModule\Models\Param\TextParamEntity",
 * 	"5"="CertificatesModule\Models\Param\DateTimeParamEntity"
 * })
 */
abstract class ParamEntity extends \Brosland\Model\Entity
{
	/**
	 * @ORM\ManyToOne(
	 * 	targetEntity="CertificatesModule\Models\ParamType\ParamTypeEntity",
	 * 	fetch="EAGER"
	 * )
	 * @var ParamTypeEntity
	 */
	private $paramType;
	/**
	 * @ORM\ManyToOne(
	 * 	targetEntity="CertificatesModule\Models\Certificate\CertificateEntity",
	 * 	inversedBy="params"
	 * )
	 * @var CertificateEntity
	 */
	private $certificate;


	/**
	 * @param ParamTypeEntity $paramType
	 * @param CertificateEntity $certificate
	 */
	public function __construct(ParamTypeEntity $paramType, CertificateEntity $certificate)
	{
		$this->paramType = $paramType;
		$this->certificate = $certificate;
	}

	/**
	 * @return ParamTypeEntity
	 */
	public function getParamType()
	{
		return $this->paramType;
	}

	/**
	 * @return CertificateEntity
	 */
	public function getCertificate()
	{
		return $this->certificate;
	}
}