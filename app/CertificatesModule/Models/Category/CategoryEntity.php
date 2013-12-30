<?php
namespace CertificatesModule\Models\Category;

use Doctrine\Common\Collections\ArrayCollection,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_Category")
 */
class CategoryEntity extends \Brosland\Model\Entity
{
	/**
	 * @ORM\Column(unique=TRUE)
	 * @var string
	 */
	private $name;
	/**
	 * @ORM\Column(unique=TRUE, length=64)
	 * @var string
	 */
	private $codePrefix;
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	private $description;
	/**
	 * @ORM\OneToMany(
	 * 	targetEntity="CertificatesModule\Models\ParamType\ParamTypeEntity",
	 * 	fetch="EXTRA_LAZY", cascade="ALL", mappedBy="certificateTypeCategory"
	 * )
	 * @var ArrayCollection
	 */
	private $certificateTypes;


	/**
	 * @param string $name
	 * @param string $codePrefix
	 */
	public function __construct($name, $codePrefix)
	{
		$this->name = $name;
		$this->codePrefix = $codePrefix;
		$this->certificateTypes = new ArrayCollection();
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
	public function getCodePrefix()
	{
		return $this->codePrefix;
	}

	/**
	 * @param string $codePrefix
	 * @return self
	 */
	public function setCodePrefix($codePrefix)
	{
		$this->codePrefix = $codePrefix;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return self
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getCertificateTypes()
	{
		return $this->certificateTypes;
	}
}