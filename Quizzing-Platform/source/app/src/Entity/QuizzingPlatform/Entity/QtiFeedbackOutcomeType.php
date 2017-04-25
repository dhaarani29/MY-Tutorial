<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiFeedbackOutcomeType
 *
 * @ORM\Table(name="qti_feedback_outcome_type")
 * @ORM\Entity
 */
class QtiFeedbackOutcomeType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="outcome_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $outcomeTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="outcome_type_name", type="string", length=50, nullable=false)
     */
    private $outcomeTypeName;

    /**
     * @var string
     *
     * @ORM\Column(name="outcome_type_desc", type="string", length=255, nullable=false)
     */
    private $outcomeTypeDesc;

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
     * Get outcomeTypeId
     *
     * @return integer
     */
    public function getOutcomeTypeId()
    {
        return $this->outcomeTypeId;
    }

    /**
     * Set outcomeTypeName
     *
     * @param string $outcomeTypeName
     *
     * @return QtiFeedbackOutcomeType
     */
    public function setOutcomeTypeName($outcomeTypeName)
    {
        $this->outcomeTypeName = $outcomeTypeName;

        return $this;
    }

    /**
     * Get outcomeTypeName
     *
     * @return string
     */
    public function getOutcomeTypeName()
    {
        return $this->outcomeTypeName;
    }

    /**
     * Set outcomeTypeDesc
     *
     * @param string $outcomeTypeDesc
     *
     * @return QtiFeedbackOutcomeType
     */
    public function setOutcomeTypeDesc($outcomeTypeDesc)
    {
        $this->outcomeTypeDesc = $outcomeTypeDesc;

        return $this;
    }

    /**
     * Get outcomeTypeDesc
     *
     * @return string
     */
    public function getOutcomeTypeDesc()
    {
        return $this->outcomeTypeDesc;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiFeedbackOutcomeType
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
     * @return QtiFeedbackOutcomeType
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
     * @return QtiFeedbackOutcomeType
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
     * @return QtiFeedbackOutcomeType
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
