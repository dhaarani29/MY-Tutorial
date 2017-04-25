<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemSimpleChoice
 *
 * @ORM\Table(name="qti_item_simple_choice", indexes={@ORM\Index(name="fk__question_multiple_choice_options_question_idx", columns={"item_pk_id"})})
 * @ORM\Entity
 */
class QtiItemSimpleChoice
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
     * @ORM\Column(name="resource_identifier", type="string", length=45, nullable=true)
     */
    private $resourceIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=10, nullable=true)
     */
    private $value;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fixed", type="boolean", nullable=true)
     */
    private $fixed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="correct", type="boolean", nullable=true)
     */
    private $correct;

    /**
     * @var string
     *
     * @ORM\Column(name="rationale", type="text", length=65535, nullable=true)
     */
    private $rationale;

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
     * @var integer
     *
     * @ORM\Column(name="choice_score", type="integer", nullable=true)
     */
    private $choiceScore;

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
     * Set resourceIdentifier
     *
     * @param string $resourceIdentifier
     *
     * @return QtiItemSimpleChoice
     */
    public function setResourceIdentifier($resourceIdentifier)
    {
        $this->resourceIdentifier = $resourceIdentifier;

        return $this;
    }

    /**
     * Get resourceIdentifier
     *
     * @return string
     */
    public function getResourceIdentifier()
    {
        return $this->resourceIdentifier;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return QtiItemSimpleChoice
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return QtiItemSimpleChoice
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set fixed
     *
     * @param boolean $fixed
     *
     * @return QtiItemSimpleChoice
     */
    public function setFixed($fixed)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return boolean
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * Set correct
     *
     * @param boolean $correct
     *
     * @return QtiItemSimpleChoice
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return boolean
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set rationale
     *
     * @param string $rationale
     *
     * @return QtiItemSimpleChoice
     */
    public function setRationale($rationale)
    {
        $this->rationale = $rationale;

        return $this;
    }

    /**
     * Get rationale
     *
     * @return string
     */
    public function getRationale()
    {
        return $this->rationale;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemSimpleChoice
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
     * @return QtiItemSimpleChoice
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
     * @return QtiItemSimpleChoice
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
     * @return QtiItemSimpleChoice
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
     * Set choiceScore
     *
     * @param integer $choiceScore
     *
     * @return QtiItemSimpleChoice
     */
    public function setChoiceScore($choiceScore)
    {
        $this->choiceScore = $choiceScore;

        return $this;
    }

    /**
     * Get choiceScore
     *
     * @return integer
     */
    public function getChoiceScore()
    {
        return $this->choiceScore;
    }

    /**
     * Set itemPk
     *
     * @param \QuizzingPlatform\Entity\QtiItem $itemPk
     *
     * @return QtiItemSimpleChoice
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
