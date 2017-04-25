<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiUserTestItemProgress
 *
 * @ORM\Table(name="qti_user_test_item_progress", indexes={@ORM\Index(name="fk__qti_user_test_item_progress__qti_user_test_items_idx", columns={"user_test_item_id"})})
 * @ORM\Entity
 */
class QtiUserTestItemProgress
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
     * @ORM\Column(name="attempt_count", type="integer", nullable=true)
     */
    private $attemptCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var integer
     *
     * @ORM\Column(name="correct", type="smallint", nullable=true)
     */
    private $correct;

    /**
     * @var integer
     *
     * @ORM\Column(name="time_spent", type="integer", nullable=true)
     */
    private $timeSpent;

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
     * @var \QuizzingPlatform\Entity\QtiUserTestItems
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiUserTestItems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_test_item_id", referencedColumnName="id")
     * })
     */
    private $userTestItem;



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
     * Set attemptCount
     *
     * @param integer $attemptCount
     *
     * @return QtiUserTestItemProgress
     */
    public function setAttemptCount($attemptCount)
    {
        $this->attemptCount = $attemptCount;

        return $this;
    }

    /**
     * Get attemptCount
     *
     * @return integer
     */
    public function getAttemptCount()
    {
        return $this->attemptCount;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return QtiUserTestItemProgress
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set correct
     *
     * @param integer $correct
     *
     * @return QtiUserTestItemProgress
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return integer
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set timeSpent
     *
     * @param integer $timeSpent
     *
     * @return QtiUserTestItemProgress
     */
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;

        return $this;
    }

    /**
     * Get timeSpent
     *
     * @return integer
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiUserTestItemProgress
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
     * @return QtiUserTestItemProgress
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
     * @return QtiUserTestItemProgress
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
     * @return QtiUserTestItemProgress
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
     * Set userTestItem
     *
     * @param \QuizzingPlatform\Entity\QtiUserTestItems $userTestItem
     *
     * @return QtiUserTestItemProgress
     */
    public function setUserTestItem(\QuizzingPlatform\Entity\QtiUserTestItems $userTestItem = null)
    {
        $this->userTestItem = $userTestItem;

        return $this;
    }

    /**
     * Get userTestItem
     *
     * @return \QuizzingPlatform\Entity\QtiUserTestItems
     */
    public function getUserTestItem()
    {
        return $this->userTestItem;
    }
}
