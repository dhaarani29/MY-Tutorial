<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemSimpleChoiceAssets
 *
 * @ORM\Table(name="qti_item_simple_choice_assets", indexes={@ORM\Index(name="fk__qti_item_simple_choice_assests__qti_item_simple_choice_idx", columns={"item_simple_choice_id"}), @ORM\Index(name="asset_id", columns={"asset_id"})})
 * @ORM\Entity
 */
class QtiItemSimpleChoiceAssets
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
     * @var \QuizzingPlatform\Entity\QtiItemSimpleChoice
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItemSimpleChoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_simple_choice_id", referencedColumnName="id")
     * })
     */
    private $itemSimpleChoice;

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
     * @return QtiItemSimpleChoiceAssets
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
     * @return QtiItemSimpleChoiceAssets
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
     * @return QtiItemSimpleChoiceAssets
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
     * @return QtiItemSimpleChoiceAssets
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
     * Set itemSimpleChoice
     *
     * @param \QuizzingPlatform\Entity\QtiItemSimpleChoice $itemSimpleChoice
     *
     * @return QtiItemSimpleChoiceAssets
     */
    public function setItemSimpleChoice(\QuizzingPlatform\Entity\QtiItemSimpleChoice $itemSimpleChoice = null)
    {
        $this->itemSimpleChoice = $itemSimpleChoice;

        return $this;
    }

    /**
     * Get itemSimpleChoice
     *
     * @return \QuizzingPlatform\Entity\QtiItemSimpleChoice
     */
    public function getItemSimpleChoice()
    {
        return $this->itemSimpleChoice;
    }

    /**
     * Set asset
     *
     * @param \QuizzingPlatform\Entity\CmnAsset $asset
     *
     * @return QtiItemSimpleChoiceAssets
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
