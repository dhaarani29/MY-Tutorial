<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnMetadata
 *
 * @ORM\Table(name="cmn_metadata", indexes={@ORM\Index(name="fk__metadata__metadata_type_idx", columns={"metadata_type_id"}), @ORM\Index(name="metadata_datatype_id", columns={"metadata_datatype_id"})})
 * @ORM\Entity
 */
class CmnMetadata
{
    /**
     * @var integer
     *
     * @ORM\Column(name="metadata_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $metadataId;

    /**
     * @var string
     *
     * @ORM\Column(name="metadata_name", type="string", length=50, nullable=false)
     */
    private $metadataName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="display_label", type="string", length=255, nullable=false)
     */
    private $displayLabel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mandatory", type="boolean", nullable=true)
     */
    private $mandatory;

    /**
     * @var boolean
     *
     * @ORM\Column(name="multi_select", type="boolean", nullable=true)
     */
    private $multiSelect;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effective_date", type="datetime", nullable=true)
     */
    private $effectiveDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", nullable=true)
     */
    private $modifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime", nullable=true)
     */
    private $modifiedDate;

    /**
     * @var \QuizzingPlatform\Entity\CmnMetadataType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnMetadataType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="metadata_type_id", referencedColumnName="metadata_type_id")
     * })
     */
    private $metadataType;

    /**
     * @var \QuizzingPlatform\Entity\CmnMetadataDatatype
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnMetadataDatatype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="metadata_datatype_id", referencedColumnName="metadata_datatype_id")
     * })
     */
    private $metadataDatatype;



    /**
     * Get metadataId
     *
     * @return integer
     */
    public function getMetadataId()
    {
        return $this->metadataId;
    }

    /**
     * Set metadataName
     *
     * @param string $metadataName
     *
     * @return CmnMetadata
     */
    public function setMetadataName($metadataName)
    {
        $this->metadataName = $metadataName;

        return $this;
    }

    /**
     * Get metadataName
     *
     * @return string
     */
    public function getMetadataName()
    {
        return $this->metadataName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CmnMetadata
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set displayLabel
     *
     * @param string $displayLabel
     *
     * @return CmnMetadata
     */
    public function setDisplayLabel($displayLabel)
    {
        $this->displayLabel = $displayLabel;

        return $this;
    }

    /**
     * Get displayLabel
     *
     * @return string
     */
    public function getDisplayLabel()
    {
        return $this->displayLabel;
    }

    /**
     * Set mandatory
     *
     * @param boolean $mandatory
     *
     * @return CmnMetadata
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * Get mandatory
     *
     * @return boolean
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Set multiSelect
     *
     * @param boolean $multiSelect
     *
     * @return CmnMetadata
     */
    public function setMultiSelect($multiSelect)
    {
        $this->multiSelect = $multiSelect;

        return $this;
    }

    /**
     * Get multiSelect
     *
     * @return boolean
     */
    public function getMultiSelect()
    {
        return $this->multiSelect;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return CmnMetadata
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set effectiveDate
     *
     * @param \DateTime $effectiveDate
     *
     * @return CmnMetadata
     */
    public function setEffectiveDate($effectiveDate)
    {
        $this->effectiveDate = $effectiveDate;

        return $this;
    }

    /**
     * Get effectiveDate
     *
     * @return \DateTime
     */
    public function getEffectiveDate()
    {
        return $this->effectiveDate;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return CmnMetadata
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
     * @return CmnMetadata
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
     * @return CmnMetadata
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
     * @return CmnMetadata
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
     * Set metadataType
     *
     * @param \QuizzingPlatform\Entity\CmnMetadataType $metadataType
     *
     * @return CmnMetadata
     */
    public function setMetadataType(\QuizzingPlatform\Entity\CmnMetadataType $metadataType = null)
    {
        $this->metadataType = $metadataType;

        return $this;
    }

    /**
     * Get metadataType
     *
     * @return \QuizzingPlatform\Entity\CmnMetadataType
     */
    public function getMetadataType()
    {
        return $this->metadataType;
    }

    /**
     * Set metadataDatatype
     *
     * @param \QuizzingPlatform\Entity\CmnMetadataDatatype $metadataDatatype
     *
     * @return CmnMetadata
     */
    public function setMetadataDatatype(\QuizzingPlatform\Entity\CmnMetadataDatatype $metadataDatatype = null)
    {
        $this->metadataDatatype = $metadataDatatype;

        return $this;
    }

    /**
     * Get metadataDatatype
     *
     * @return \QuizzingPlatform\Entity\CmnMetadataDatatype
     */
    public function getMetadataDatatype()
    {
        return $this->metadataDatatype;
    }
}
