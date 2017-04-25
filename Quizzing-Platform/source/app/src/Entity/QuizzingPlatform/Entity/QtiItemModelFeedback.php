<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemModelFeedback
 *
 * @ORM\Table(name="qti_item_model_feedback", indexes={@ORM\Index(name="qti_item_model_feedback__qti_item__1_idx", columns={"item_pk_id"}), @ORM\Index(name="outcome_type_id", columns={"outcome_type_id"})})
 * @ORM\Entity
 */
class QtiItemModelFeedback
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
     * @var boolean
     *
     * @ORM\Column(name="show_hide", type="boolean", nullable=true)
     */
    private $showHide;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback_text", type="string", length=255, nullable=true)
     */
    private $feedbackText;

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
     * @var \QuizzingPlatform\Entity\QtiFeedbackOutcomeType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiFeedbackOutcomeType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="outcome_type_id", referencedColumnName="outcome_type_id")
     * })
     */
    private $outcomeType;



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
     * Set showHide
     *
     * @param boolean $showHide
     *
     * @return QtiItemModelFeedback
     */
    public function setShowHide($showHide)
    {
        $this->showHide = $showHide;

        return $this;
    }

    /**
     * Get showHide
     *
     * @return boolean
     */
    public function getShowHide()
    {
        return $this->showHide;
    }

    /**
     * Set feedbackText
     *
     * @param string $feedbackText
     *
     * @return QtiItemModelFeedback
     */
    public function setFeedbackText($feedbackText)
    {
        $this->feedbackText = $feedbackText;

        return $this;
    }

    /**
     * Get feedbackText
     *
     * @return string
     */
    public function getFeedbackText()
    {
        return $this->feedbackText;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemModelFeedback
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
     * @return QtiItemModelFeedback
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
     * @return QtiItemModelFeedback
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
     * @return QtiItemModelFeedback
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
     * @return QtiItemModelFeedback
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
     * Set outcomeType
     *
     * @param \QuizzingPlatform\Entity\QtiFeedbackOutcomeType $outcomeType
     *
     * @return QtiItemModelFeedback
     */
    public function setOutcomeType(\QuizzingPlatform\Entity\QtiFeedbackOutcomeType $outcomeType = null)
    {
        $this->outcomeType = $outcomeType;

        return $this;
    }

    /**
     * Get outcomeType
     *
     * @return \QuizzingPlatform\Entity\QtiFeedbackOutcomeType
     */
    public function getOutcomeType()
    {
        return $this->outcomeType;
    }
}
