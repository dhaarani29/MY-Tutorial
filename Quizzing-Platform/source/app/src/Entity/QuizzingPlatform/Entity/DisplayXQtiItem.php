<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisplayXQtiItem
 *
 * @ORM\Table(name="display_x_qti_item", indexes={@ORM\Index(name="fk__display_x_qti_item__display_locale_idx", columns={"locale_id"}), @ORM\Index(name="IDX_D57E83A0126F525E", columns={"item_pk_id"})})
 * @ORM\Entity
 */
class DisplayXQtiItem
{
    /**
     * @var string
     *
     * @ORM\Column(name="locale_id", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $localeId;

    /**
     * @var string
     *
     * @ORM\Column(name="display_x_item_name", type="string", length=50, nullable=true)
     */
    private $displayXItemName;

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
     * @var \QuizzingPlatform\Entity\QtiItem
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="QuizzingPlatform\Entity\QtiItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_pk_id", referencedColumnName="id")
     * })
     */
    private $itemPk;



    /**
     * Set localeId
     *
     * @param string $localeId
     *
     * @return DisplayXQtiItem
     */
    public function setLocaleId($localeId)
    {
        $this->localeId = $localeId;

        return $this;
    }

    /**
     * Get localeId
     *
     * @return string
     */
    public function getLocaleId()
    {
        return $this->localeId;
    }

    /**
     * Set displayXItemName
     *
     * @param string $displayXItemName
     *
     * @return DisplayXQtiItem
     */
    public function setDisplayXItemName($displayXItemName)
    {
        $this->displayXItemName = $displayXItemName;

        return $this;
    }

    /**
     * Get displayXItemName
     *
     * @return string
     */
    public function getDisplayXItemName()
    {
        return $this->displayXItemName;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return DisplayXQtiItem
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
     * @return DisplayXQtiItem
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
     * @return DisplayXQtiItem
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
     * @return DisplayXQtiItem
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
     * Set itemPk
     *
     * @param \QuizzingPlatform\Entity\QtiItem $itemPk
     *
     * @return DisplayXQtiItem
     */
    public function setItemPk(\QuizzingPlatform\Entity\QtiItem $itemPk)
    {
        $this->itemPk = $itemPk;

        return $this;
    }

    /**
     * Get itemPk
     *
     * @return \QuizzingPlatform\Entity\QtiItem
     */
    public function getItemPk()
    {
        return $this->itemPk;
    }
}
