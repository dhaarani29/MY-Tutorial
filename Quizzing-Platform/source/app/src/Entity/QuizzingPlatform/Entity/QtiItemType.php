<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemType
 *
 * @ORM\Table(name="qti_item_type")
 * @ORM\Entity
 */
class QtiItemType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="item_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $itemTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="item_type_name", type="string", length=50, nullable=false)
     */
    private $itemTypeName;

    /**
     * @var string
     *
     * @ORM\Column(name="item_type_description", type="text", length=65535, nullable=true)
     */
    private $itemTypeDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="label_text", type="string", length=50, nullable=false)
     */
    private $labelText;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_type_display_order", type="integer", nullable=false)
     */
    private $itemTypeDisplayOrder;

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
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';



    /**
     * Get itemTypeId
     *
     * @return integer
     */
    public function getItemTypeId()
    {
        return $this->itemTypeId;
    }

    /**
     * Set itemTypeName
     *
     * @param string $itemTypeName
     *
     * @return QtiItemType
     */
    public function setItemTypeName($itemTypeName)
    {
        $this->itemTypeName = $itemTypeName;

        return $this;
    }

    /**
     * Get itemTypeName
     *
     * @return string
     */
    public function getItemTypeName()
    {
        return $this->itemTypeName;
    }

    /**
     * Set itemTypeDescription
     *
     * @param string $itemTypeDescription
     *
     * @return QtiItemType
     */
    public function setItemTypeDescription($itemTypeDescription)
    {
        $this->itemTypeDescription = $itemTypeDescription;

        return $this;
    }

    /**
     * Get itemTypeDescription
     *
     * @return string
     */
    public function getItemTypeDescription()
    {
        return $this->itemTypeDescription;
    }

    /**
     * Set labelText
     *
     * @param string $labelText
     *
     * @return QtiItemType
     */
    public function setLabelText($labelText)
    {
        $this->labelText = $labelText;

        return $this;
    }

    /**
     * Get labelText
     *
     * @return string
     */
    public function getLabelText()
    {
        return $this->labelText;
    }

    /**
     * Set itemTypeDisplayOrder
     *
     * @param integer $itemTypeDisplayOrder
     *
     * @return QtiItemType
     */
    public function setItemTypeDisplayOrder($itemTypeDisplayOrder)
    {
        $this->itemTypeDisplayOrder = $itemTypeDisplayOrder;

        return $this;
    }

    /**
     * Get itemTypeDisplayOrder
     *
     * @return integer
     */
    public function getItemTypeDisplayOrder()
    {
        return $this->itemTypeDisplayOrder;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemType
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
     * @return QtiItemType
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
     * @return QtiItemType
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
     * @return QtiItemType
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
     * Set status
     *
     * @param boolean $status
     *
     * @return QtiItemType
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
}
