<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnMetadataType
 *
 * @ORM\Table(name="cmn_metadata_type")
 * @ORM\Entity
 */
class CmnMetadataType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="metadata_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $metadataTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="metadata_type_name", type="string", length=50, nullable=false)
     */
    private $metadataTypeName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="metadata_label", type="string", length=50, nullable=false)
     */
    private $metadataLabel;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_order", type="integer", nullable=false)
     */
    private $displayOrder;

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
     * Get metadataTypeId
     *
     * @return integer
     */
    public function getMetadataTypeId()
    {
        return $this->metadataTypeId;
    }

    /**
     * Set metadataTypeName
     *
     * @param string $metadataTypeName
     *
     * @return CmnMetadataType
     */
    public function setMetadataTypeName($metadataTypeName)
    {
        $this->metadataTypeName = $metadataTypeName;

        return $this;
    }

    /**
     * Get metadataTypeName
     *
     * @return string
     */
    public function getMetadataTypeName()
    {
        return $this->metadataTypeName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CmnMetadataType
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
     * Set metadataLabel
     *
     * @param string $metadataLabel
     *
     * @return CmnMetadataType
     */
    public function setMetadataLabel($metadataLabel)
    {
        $this->metadataLabel = $metadataLabel;

        return $this;
    }

    /**
     * Get metadataLabel
     *
     * @return string
     */
    public function getMetadataLabel()
    {
        return $this->metadataLabel;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     *
     * @return CmnMetadataType
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return integer
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return CmnMetadataType
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
     * @return CmnMetadataType
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
     * @return CmnMetadataType
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
     * @return CmnMetadataType
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
}
