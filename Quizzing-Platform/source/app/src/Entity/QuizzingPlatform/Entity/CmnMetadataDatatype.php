<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnMetadataDatatype
 *
 * @ORM\Table(name="cmn_metadata_datatype", indexes={@ORM\Index(name="metadata_datatype_id", columns={"metadata_datatype_id"})})
 * @ORM\Entity
 */
class CmnMetadataDatatype
{
    /**
     * @var integer
     *
     * @ORM\Column(name="metadata_datatype_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $metadataDatatypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="metadata_datatype_name", type="string", length=50, nullable=false)
     */
    private $metadataDatatypeName;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_order", type="integer", nullable=false)
     */
    private $displayOrder;

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
     * Get metadataDatatypeId
     *
     * @return integer
     */
    public function getMetadataDatatypeId()
    {
        return $this->metadataDatatypeId;
    }

    /**
     * Set metadataDatatypeName
     *
     * @param string $metadataDatatypeName
     *
     * @return CmnMetadataDatatype
     */
    public function setMetadataDatatypeName($metadataDatatypeName)
    {
        $this->metadataDatatypeName = $metadataDatatypeName;

        return $this;
    }

    /**
     * Get metadataDatatypeName
     *
     * @return string
     */
    public function getMetadataDatatypeName()
    {
        return $this->metadataDatatypeName;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     *
     * @return CmnMetadataDatatype
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
     * @return CmnMetadataDatatype
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
     * @return CmnMetadataDatatype
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
     * @return CmnMetadataDatatype
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
     * @return CmnMetadataDatatype
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
