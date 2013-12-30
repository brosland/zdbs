<?php
namespace CertificatesModule\Models\Certificate;

use CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	Doctrine\ORM\Mapping as ORM,
	Nette\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_Certificate")
 */
class CertificateEntity extends \Brosland\Model\Entity
{
	/**
	 * @ORM\ManyToOne(
	 * 	targetEntity="CertificatesModule\Models\CertificateType\CertificateTypeEntity",
	 * 	fetch="EAGER"
	 * )
	 * @var CertificateTypeEntity
	 */
	private $certificateType;
	/**
	 * @ORM\Column(length=8, unique=TRUE)
	 * @var string
	 */
	private $code;
	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	private $created;
	/**
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 * @var DateTime
	 */
	private $expiration = NULL;


	/**
	 * @param CertificateTypeEntity $certificateType
	 * @param string $code
	 */
	public function __construct(CertificateTypeEntity $certificateType, $code)
	{
		$this->certificateType = $certificateType;
		$this->code = $code;
		$this->created = new DateTime();
	}

	/**
	 * @return CertificateTypeEntity 
	 */
	public function getCertificateType()
	{
		return $this->certificateType;
	}

	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 * @return self
	 */
	public function setCode($code)
	{
		$this->code = $code;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getFullCode()
	{
		return $this->certificateType->getCategory()->getCodePrefix() . $this->code;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @param DateTime $created
	 * @return self
	 */
	public function setCreated($created)
	{
		$this->created = $created;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getExpiration()
	{
		return $this->expiration;
	}

	/**
	 * @param DateTime $expiration
	 * @return self
	 */
	public function setExpiration($expiration)
	{
		$this->expiration = $expiration;
		return $this;
	}
}