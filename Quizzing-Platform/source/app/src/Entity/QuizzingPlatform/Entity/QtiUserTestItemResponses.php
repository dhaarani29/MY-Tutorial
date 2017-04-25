<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiUserTestItemResponses
 *
 * @ORM\Table(name="qti_user_test_item_responses", indexes={@ORM\Index(name="qti_test_item_response__qti_test_item_progress__1_idx", columns={"user_test_item_progress_id"})})
 * @ORM\Entity
 */
class QtiUserTestItemResponses
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
     * @ORM\Column(name="value", type="string", length=50, nullable=true)
     */
    private $value;

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
     * @var \QuizzingPlatform\Entity\QtiUserTestItemProgress
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiUserTestItemProgress")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_test_item_progress_id", referencedColumnName="id")
     * })
     */
    private $userTestItemProgress;



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
     * Set value
     *
     * @param string $value
     *
     * @return QtiUserTestItemResponses
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
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiUserTestItemResponses
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
     * @return QtiUserTestItemResponses
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
     * @return QtiUserTestItemResponses
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
     * @return QtiUserTestItemResponses
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
     * Set userTestItemProgress
     *
     * @param \QuizzingPlatform\Entity\QtiUserTestItemProgress $userTestItemProgress
     *
     * @return QtiUserTestItemResponses
     */
    public function setUserTestItemProgress(\QuizzingPlatform\Entity\QtiUserTestItemProgress $userTestItemProgress = null)
    {
        $this->userTestItemProgress = $userTestItemProgress;

        return $this;
    }

    /**
     * Get userTestItemProgress
     *
     * @return \QuizzingPlatform\Entity\QtiUserTestItemProgress
     */
    public function getUserTestItemProgress()
    {
        return $this->userTestItemProgress;
    }
}
