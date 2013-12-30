<?php
namespace CertificatesModule\Models\ParamType;

use CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_ParamType")
 */
class ParamTypeEntity extends \Brosland\Model\Entity
{
	/**
	 * @ORM\Column
	 * @var string
	 */
	private $name;
	/**
	 * @ORM\Column
	 * @var string
	 */
	private $label;
	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $ordering = 0;
	/**
	 * @ORM\Column(type="boolean")
	 * @var string
	 */
	private $required = FALSE;
	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $paramTypeId;
	/**
	 * @ORM\ManyToOne(
	 * 	targetEntity="CertificatesModule\Models\CertificateType\CertificateTypeEntity",
	 * 	inversedBy="paramTypes"
	 * )
	 * @ORM\JoinColumn(name="certificateType_id")
	 * @var CertificateTypeEntity
	 */
	private $certificateType;


	/**
	 * @param string $name
	 * @param string $label
	 * @param int $paramTypeId
	 * @param CertificateTypeEntity $certificateType
	 */
	public function __construct($name, $label, $paramTypeId, CertificateTypeEntity $certificateType)
	{
		$this->name = $name;
		$this->label = $label;
		$this->paramTypeId = $paramTypeId;
		$this->certificateType = $certificateType;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 * @return self
	 */
	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOrdering()
	{
		return $this->ordering;
	}

	/**
	 * @param int $ordering
	 * @return self
	 */
	public function setOrdering($ordering)
	{
		$this->ordering = (int) $ordering;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isRequired()
	{
		return $this->required;
	}

	/**
	 * @param bool $required
	 * @return self
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getParamTypeId()
	{
		return $this->paramTypeId;
	}

	/**
	 * @param int $paramTypeId
	 * @return self
	 */
	public function setParamTypeId($paramTypeId)
	{
		$this->paramTypeId = (int) $paramTypeId;
		return $this;
	}

	/**
	 * @return CertificateTypeEntity
	 */
	public function getCertificateType()
	{
		return $this->certificateType;
	}
}