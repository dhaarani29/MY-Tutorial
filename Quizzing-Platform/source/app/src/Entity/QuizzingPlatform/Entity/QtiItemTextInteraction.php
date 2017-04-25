<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemTextInteraction
 *
 * @ORM\Table(name="qti_item_text_interaction", indexes={@ORM\Index(name="fk__qti_item_text_interaction__qti_item_idx", columns={"item_pk_id"})})
 * @ORM\Entity
 */
class QtiItemTextInteraction
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
     * @var string
     *
     * @ORM\Column(name="qti_resouce_identifier", type="string", length=45, nullable=true)
     */
    private $qtiResouceIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(name="prompt_text", type="string", length=255, nullable=true)
     */
    private $promptText;

    /**
     * @var string
     *
     * @ORM\Column(name="string_identifier", type="string", length=255, nullable=true)
     */
    private $stringIdentifier;

    /**
     * @var integer
     *
     * @ORM\Column(name="expected_length", type="integer", nullable=true)
     */
    private $expectedLength;

    /**
     * @var string
     *
     * @ORM\Column(name="pattern_mask", type="string", length=45, nullable=true)
     */
    private $patternMask;

    /**
     * @var string
     *
     * @ORM\Column(name="place_holder_text", type="string", length=255, nullable=true)
     */
    private $placeHolderText;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set qtiResouceIdentifier
     *
     * @param string $qtiResouceIdentifier
     *
     * @return QtiItemTextInteraction
     */
    public function setQtiResouceIdentifier($qtiResouceIdentifier)
    {
        $this->qtiResouceIdentifier = $qtiResouceIdentifier;

        return $this;
    }

    /**
     * Get qtiResouceIdentifier
     *
     * @return string
     */
    public function getQtiResouceIdentifier()
    {
        return $this->qtiResouceIdentifier;
    }

    /**
     * Set promptText
     *
     * @param string $promptText
     *
     * @return QtiItemTextInteraction
     */
    public function setPromptText($promptText)
    {
        $this->promptText = $promptText;

        return $this;
    }

    /**
     * Get promptText
     *
     * @return string
     */
    public function getPromptText()
    {
        return $this->promptText;
    }

    /**
     * Set stringIdentifier
     *
     * @param string $stringIdentifier
     *
     * @return QtiItemTextInteraction
     */
    public function setStringIdentifier($stringIdentifier)
    {
        $this->stringIdentifier = $stringIdentifier;

        return $this;
    }

    /**
     * Get stringIdentifier
     *
     * @return string
     */
    public function getStringIdentifier()
    {
        return $this->stringIdentifier;
    }

    /**
     * Set expectedLength
     *
     * @param integer $expectedLength
     *
     * @return QtiItemTextInteraction
     */
    public function setExpectedLength($expectedLength)
    {
        $this->expectedLength = $expectedLength;

        return $this;
    }

    /**
     * Get expectedLength
     *
     * @return integer
     */
    public function getExpectedLength()
    {
        return $this->expectedLength;
    }

    /**
     * Set patternMask
     *
     * @param string $patternMask
     *
     * @return QtiItemTextInteraction
     */
    public function setPatternMask($patternMask)
    {
        $this->patternMask = $patternMask;

        return $this;
    }

    /**
     * Get patternMask
     *
     * @return string
     */
    public function getPatternMask()
    {
        return $this->patternMask;
    }

    /**
     * Set placeHolderText
     *
     * @param string $placeHolderText
     *
     * @return QtiItemTextInteraction
     */
    public function setPlaceHolderText($placeHolderText)
    {
        $this->placeHolderText = $placeHolderText;

        return $this;
    }

    /**
     * Get placeHolderText
     *
     * @return string
     */
    public function getPlaceHolderText()
    {
        return $this->placeHolderText;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemTextInteraction
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
     * @return QtiItemTextInteraction
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
     * @return QtiItemTextInteraction
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
     * @return QtiItemTextInteraction
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
     * @return QtiItemTextInteraction
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
}
