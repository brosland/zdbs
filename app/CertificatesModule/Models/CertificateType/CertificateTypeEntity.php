<?php
namespace CertificatesModule\Models\CertificateType;

use CertificatesModule\Models\Category\CategoryEntity,
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Certificates_CertificateType")
 */
class CertificateTypeEntity extends \Brosland\Model\Entity
{
	/**
	 * @ORM\ManyToOne(
	 * 	targetEntity="CertificatesModule\Models\Category\CategoryEntity",
	 * 	inversedBy="certificateTypes"
	 * )
	 * @ORM\JoinColumn(name="category_id")
	 * @var CategoryEntity
	 */
	private $category;
	/**
	 * @ORM\Column(unique=TRUE)
	 * @var string
	 */
	private $name;
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	private $description;
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	private $template;
	/**
	 * @ORM\OneToMany(
	 * 	targetEntity="CertificatesModule\Models\ParamType\ParamTypeEntity",
	 * 	fetch="EXTRA_LAZY", cascade="ALL", mappedBy="certificateType"
	 * )
	 * @ORM\OrderBy({"ordering"="ASC"})
	 * @var ArrayCollection
	 */
	private $paramTypes;


	/**
	 * @param CategoryEntity $category
	 * @param string $name
	 * @param string $template
	 */
	public function __construct(CategoryEntity $category, $name, $template)
	{
		$this->category = $category;
		$this->name = $name;
		$this->template = $template;
		$this->paramTypes = new ArrayCollection();
	}

	/**
	 * @return CategoryEntity
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * @param CategoryEntity $category
	 * @return self
	 */
	public function setCategory(CategoryEntity $category)
	{
		$this->category = $category;
		return $this;
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
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * @param string $template
	 * @return self
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getParamTypes()
	{
		return $this->paramTypes;
	}
}