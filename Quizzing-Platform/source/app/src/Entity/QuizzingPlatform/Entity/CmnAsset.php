<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnAsset
 *
 * @ORM\Table(name="cmn_asset", indexes={@ORM\Index(name="fk_asset_asset_type_1_idx", columns={"asset_type_id"})})
 * @ORM\Entity
 */
class CmnAsset
{
    /**
     * @var integer
     *
     * @ORM\Column(name="asset_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $assetId;

    /**
     * @var string
     *
     * @ORM\Column(name="asset_name", type="string", length=50, nullable=false)
     */
    private $assetName;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=false)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="alt_title", type="string", length=255, nullable=true)
     */
    private $altTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=255, nullable=false)
     */
    private $mimeType;

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
     * @var \QuizzingPlatform\Entity\CmnAssetType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnAssetType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="asset_type_id", referencedColumnName="asset_type_id")
     * })
     */
    private $assetType;



    /**
     * Get assetId
     *
     * @return integer
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * Set assetName
     *
     * @param string $assetName
     *
     * @return CmnAsset
     */
    public function setAssetName($assetName)
    {
        $this->assetName = $assetName;

        return $this;
    }

    /**
     * Get assetName
     *
     * @return string
     */
    public function getAssetName()
    {
        return $this->assetName;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return CmnAsset
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set altTitle
     *
     * @param string $altTitle
     *
     * @return CmnAsset
     */
    public function setAltTitle($altTitle)
    {
        $this->altTitle = $altTitle;

        return $this;
    }

    /**
     * Get altTitle
     *
     * @return string
     */
    public function getAltTitle()
    {
        return $this->altTitle;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return CmnAsset
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set effectiveDate
     *
     * @param \DateTime $effectiveDate
     *
     * @return CmnAsset
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
     * @return CmnAsset
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
     * @return CmnAsset
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
     * @return CmnAsset
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
     * @return CmnAsset
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
     * Set assetType
     *
     * @param \QuizzingPlatform\Entity\CmnAssetType $assetType
     *
     * @return CmnAsset
     */
    public function setAssetType(\QuizzingPlatform\Entity\CmnAssetType $assetType = null)
    {
        $this->assetType = $assetType;

        return $this;
    }

    /**
     * Get assetType
     *
     * @return \QuizzingPlatform\Entity\CmnAssetType
     */
    public function getAssetType()
    {
        return $this->assetType;
    }
}
