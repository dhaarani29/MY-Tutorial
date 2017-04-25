<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemChoiceInteraction
 *
 * @ORM\Table(name="qti_item_choice_interaction", indexes={@ORM\Index(name="fk__question_multiple_choice_question_idx", columns={"item_pk_id"})})
 * @ORM\Entity
 */
class QtiItemChoiceInteraction
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
     * @ORM\Column(name="prompt_text", type="string", length=255, nullable=true)
     */
    private $promptText;

    /**
     * @var boolean
     *
     * @ORM\Column(name="shuffle", type="boolean", nullable=true)
     */
    private $shuffle;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_choice", type="integer", nullable=true)
     */
    private $minChoice;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_choice", type="integer", nullable=true)
     */
    private $maxChoice;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_score", type="integer", nullable=true)
     */
    private $itemScore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_partial_score", type="boolean", nullable=true)
     */
    private $isPartialScore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_negative_score", type="boolean", nullable=true)
     */
    private $isNegativeScore;

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
     * Set promptText
     *
     * @param string $promptText
     *
     * @return QtiItemChoiceInteraction
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
     * Set shuffle
     *
     * @param boolean $shuffle
     *
     * @return QtiItemChoiceInteraction
     */
    public function setShuffle($shuffle)
    {
        $this->shuffle = $shuffle;

        return $this;
    }

    /**
     * Get shuffle
     *
     * @return boolean
     */
    public function getShuffle()
    {
        return $this->shuffle;
    }

    /**
     * Set minChoice
     *
     * @param integer $minChoice
     *
     * @return QtiItemChoiceInteraction
     */
    public function setMinChoice($minChoice)
    {
        $this->minChoice = $minChoice;

        return $this;
    }

    /**
     * Get minChoice
     *
     * @return integer
     */
    public function getMinChoice()
    {
        return $this->minChoice;
    }

    /**
     * Set maxChoice
     *
     * @param integer $maxChoice
     *
     * @return QtiItemChoiceInteraction
     */
    public function setMaxChoice($maxChoice)
    {
        $this->maxChoice = $maxChoice;

        return $this;
    }

    /**
     * Get maxChoice
     *
     * @return integer
     */
    public function getMaxChoice()
    {
        return $this->maxChoice;
    }

    /**
     * Set itemScore
     *
     * @param integer $itemScore
     *
     * @return QtiItemChoiceInteraction
     */
    public function setItemScore($itemScore)
    {
        $this->itemScore = $itemScore;

        return $this;
    }

    /**
     * Get itemScore
     *
     * @return integer
     */
    public function getItemScore()
    {
        return $this->itemScore;
    }

    /**
     * Set isPartialScore
     *
     * @param boolean $isPartialScore
     *
     * @return QtiItemChoiceInteraction
     */
    public function setIsPartialScore($isPartialScore)
    {
        $this->isPartialScore = $isPartialScore;

        return $this;
    }

    /**
     * Get isPartialScore
     *
     * @return boolean
     */
    public function getIsPartialScore()
    {
        return $this->isPartialScore;
    }

    /**
     * Set isNegativeScore
     *
     * @param boolean $isNegativeScore
     *
     * @return QtiItemChoiceInteraction
     */
    public function setIsNegativeScore($isNegativeScore)
    {
        $this->isNegativeScore = $isNegativeScore;

        return $this;
    }

    /**
     * Get isNegativeScore
     *
     * @return boolean
     */
    public function getIsNegativeScore()
    {
        return $this->isNegativeScore;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemChoiceInteraction
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
     * @return QtiItemChoiceInteraction
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
     * @return QtiItemChoiceInteraction
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
     * @return QtiItemChoiceInteraction
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
     * @return QtiItemChoiceInteraction
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
