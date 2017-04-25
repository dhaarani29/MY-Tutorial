<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemAssets
 *
 * @ORM\Table(name="qti_item_assets", indexes={@ORM\Index(name="fk__question_assets__question_idx", columns={"item_pk_id"}), @ORM\Index(name="fk__question_assets__asset_idx", columns={"asset_id"})})
 * @ORM\Entity
 */
class QtiItemAssets
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
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_pk_id", referencedColumnName="id")
     * })
     */
    private $itemPk;

    /**
     * @var \QuizzingPlatform\Entity\CmnAsset
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnAsset")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="asset_id", referencedColumnName="asset_id")
     * })
     */
    private $asset;



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
     * @return QtiItemAssets
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
     * @return QtiItemAssets
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
     * @return QtiItemAssets
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
     * @return QtiItemAssets
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
     * @return QtiItemAssets
     */
    public function setItemPk(\QuizzingPlatform\Entity\QtiItem $itemPk = null)
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

    /**
     * Set asset
     *
     * @param \QuizzingPlatform\Entity\CmnAsset $asset
     *
     * @return QtiItemAssets
     */
    public function setAsset(\QuizzingPlatform\Entity\CmnAsset $asset = null)
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * Get asset
     *
     * @return \QuizzingPlatform\Entity\CmnAsset
     */
    public function getAsset()
    {
        return $this->asset;
    }
}
