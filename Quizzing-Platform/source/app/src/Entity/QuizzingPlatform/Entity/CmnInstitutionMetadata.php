<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnInstitutionMetadata
 *
 * @ORM\Table(name="cmn_institution_metadata", indexes={@ORM\Index(name="institution_id", columns={"institution_id"}), @ORM\Index(name="metadata_id", columns={"metadata_id"})})
 * @ORM\Entity
 */
class CmnInstitutionMetadata
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", nullable=false)
     */
    private $modifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime", nullable=false)
     */
    private $modifiedDate;

    /**
     * @var \QuizzingPlatform\Entity\CmnInstitutions
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnInstitutions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="institution_id", referencedColumnName="id")
     * })
     */
    private $institution;

    /**
     * @var \QuizzingPlatform\Entity\CmnMetadata
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnMetadata")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="metadata_id", referencedColumnName="metadata_id")
     * })
     */
    private $metadata;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return CmnInstitutionMetadata
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return CmnInstitutionMetadata
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set modifiedBy
     *
     * @param integer $modifiedBy
     *
     * @return CmnInstitutionMetadata
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get modifiedBy
     *
     * @return integer
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Set modifiedDate
     *
     * @param \DateTime $modifiedDate
     *
     * @return CmnInstitutionMetadata
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    /**
     * Get modifiedDate
     *
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * Set institution
     *
     * @param \QuizzingPlatform\Entity\CmnInstitutions $institution
     *
     * @return CmnInstitutionMetadata
     */
    public function setInstitution(\QuizzingPlatform\Entity\CmnInstitutions $institution = null)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return \QuizzingPlatform\Entity\CmnInstitutions
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set metadata
     *
     * @param \QuizzingPlatform\Entity\CmnMetadata $metadata
     *
     * @return CmnInstitutionMetadata
     */
    public function setMetadata(\QuizzingPlatform\Entity\CmnMetadata $metadata = null)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return \QuizzingPlatform\Entity\CmnMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
