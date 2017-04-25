<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiTestLimit
 *
 * @ORM\Table(name="qti_test_limit", indexes={@ORM\Index(name="test_pk_id", columns={"test_pk_id"})})
 * @ORM\Entity
 */
class QtiTestLimit
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
     * @ORM\Column(name="min_time", type="integer", nullable=true)
     */
    private $minTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_time", type="integer", nullable=true)
     */
    private $maxTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allow_late_submission", type="boolean", nullable=true)
     */
    private $allowLateSubmission;

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
     * @var \QuizzingPlatform\Entity\QtiTest
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiTest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="test_pk_id", referencedColumnName="id")
     * })
     */
    private $testPk;



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
     * Set minTime
     *
     * @param integer $minTime
     *
     * @return QtiTestLimit
     */
    public function setMinTime($minTime)
    {
        $this->minTime = $minTime;

        return $this;
    }

    /**
     * Get minTime
     *
     * @return integer
     */
    public function getMinTime()
    {
        return $this->minTime;
    }

    /**
     * Set maxTime
     *
     * @param integer $maxTime
     *
     * @return QtiTestLimit
     */
    public function setMaxTime($maxTime)
    {
        $this->maxTime = $maxTime;

        return $this;
    }

    /**
     * Get maxTime
     *
     * @return integer
     */
    public function getMaxTime()
    {
        return $this->maxTime;
    }

    /**
     * Set allowLateSubmission
     *
     * @param boolean $allowLateSubmission
     *
     * @return QtiTestLimit
     */
    public function setAllowLateSubmission($allowLateSubmission)
    {
        $this->allowLateSubmission = $allowLateSubmission;

        return $this;
    }

    /**
     * Get allowLateSubmission
     *
     * @return boolean
     */
    public function getAllowLateSubmission()
    {
        return $this->allowLateSubmission;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiTestLimit
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
     * @return QtiTestLimit
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
     * @return QtiTestLimit
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
     * @return QtiTestLimit
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
     * Set testPk
     *
     * @param \QuizzingPlatform\Entity\QtiTest $testPk
     *
     * @return QtiTestLimit
     */
    public function setTestPk(\QuizzingPlatform\Entity\QtiTest $testPk = null)
    {
        $this->testPk = $testPk;

        return $this;
    }

    /**
     * Get testPk
     *
     * @return \QuizzingPlatform\Entity\QtiTest
     */
    public function getTestPk()
    {
        return $this->testPk;
    }
}
