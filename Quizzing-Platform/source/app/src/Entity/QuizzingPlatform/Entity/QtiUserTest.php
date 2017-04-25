<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiUserTest
 *
 * @ORM\Table(name="qti_user_test", indexes={@ORM\Index(name="fk__qti_user_test_progress__qti_test_status_idx", columns={"test_status_id"}), @ORM\Index(name="fk__qti_user_test__org_user_profile_idx", columns={"user_id"}), @ORM\Index(name="test_pk_id", columns={"test_pk_id"})})
 * @ORM\Entity
 */
class QtiUserTest
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
     * @ORM\Column(name="total_time_spent", type="integer", nullable=true)
     */
    private $totalTimeSpent;

    /**
     * @var integer
     *
     * @ORM\Column(name="bookmark", type="integer", nullable=true)
     */
    private $bookmark;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_questions", type="integer", nullable=true)
     */
    private $totalQuestions;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_correct", type="integer", nullable=true)
     */
    private $totalCorrect;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_incorrect", type="integer", nullable=true)
     */
    private $totalIncorrect;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_unattempted", type="integer", nullable=true)
     */
    private $totalUnattempted;

    /**
     * @var integer
     *
     * @ORM\Column(name="grade", type="integer", nullable=true)
     */
    private $grade;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="test_start", type="datetime", nullable=true)
     */
    private $testStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="test_last_attempted", type="datetime", nullable=true)
     */
    private $testLastAttempted;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_deleted", type="smallint", nullable=false)
     */
    private $isDeleted = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="test_completed_date", type="datetime", nullable=true)
     */
    private $testCompletedDate;

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
     * @var \QuizzingPlatform\Entity\QtiTestStatus
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiTestStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="test_status_id", referencedColumnName="test_status_id")
     * })
     */
    private $testStatus;

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
     * @var \QuizzingPlatform\Entity\OrgUserProfile
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\OrgUserProfile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;



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
     * Set totalTimeSpent
     *
     * @param integer $totalTimeSpent
     *
     * @return QtiUserTest
     */
    public function setTotalTimeSpent($totalTimeSpent)
    {
        $this->totalTimeSpent = $totalTimeSpent;

        return $this;
    }

    /**
     * Get totalTimeSpent
     *
     * @return integer
     */
    public function getTotalTimeSpent()
    {
        return $this->totalTimeSpent;
    }

    /**
     * Set bookmark
     *
     * @param integer $bookmark
     *
     * @return QtiUserTest
     */
    public function setBookmark($bookmark)
    {
        $this->bookmark = $bookmark;

        return $this;
    }

    /**
     * Get bookmark
     *
     * @return integer
     */
    public function getBookmark()
    {
        return $this->bookmark;
    }

    /**
     * Set totalQuestions
     *
     * @param integer $totalQuestions
     *
     * @return QtiUserTest
     */
    public function setTotalQuestions($totalQuestions)
    {
        $this->totalQuestions = $totalQuestions;

        return $this;
    }

    /**
     * Get totalQuestions
     *
     * @return integer
     */
    public function getTotalQuestions()
    {
        return $this->totalQuestions;
    }

    /**
     * Set totalCorrect
     *
     * @param integer $totalCorrect
     *
     * @return QtiUserTest
     */
    public function setTotalCorrect($totalCorrect)
    {
        $this->totalCorrect = $totalCorrect;

        return $this;
    }

    /**
     * Get totalCorrect
     *
     * @return integer
     */
    public function getTotalCorrect()
    {
        return $this->totalCorrect;
    }

    /**
     * Set totalIncorrect
     *
     * @param integer $totalIncorrect
     *
     * @return QtiUserTest
     */
    public function setTotalIncorrect($totalIncorrect)
    {
        $this->totalIncorrect = $totalIncorrect;

        return $this;
    }

    /**
     * Get totalIncorrect
     *
     * @return integer
     */
    public function getTotalIncorrect()
    {
        return $this->totalIncorrect;
    }

    /**
     * Set totalUnattempted
     *
     * @param integer $totalUnattempted
     *
     * @return QtiUserTest
     */
    public function setTotalUnattempted($totalUnattempted)
    {
        $this->totalUnattempted = $totalUnattempted;

        return $this;
    }

    /**
     * Get totalUnattempted
     *
     * @return integer
     */
    public function getTotalUnattempted()
    {
        return $this->totalUnattempted;
    }

    /**
     * Set grade
     *
     * @param integer $grade
     *
     * @return QtiUserTest
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return integer
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set testStart
     *
     * @param \DateTime $testStart
     *
     * @return QtiUserTest
     */
    public function setTestStart($testStart)
    {
        $this->testStart = $testStart;

        return $this;
    }

    /**
     * Get testStart
     *
     * @return \DateTime
     */
    public function getTestStart()
    {
        return $this->testStart;
    }

    /**
     * Set testLastAttempted
     *
     * @param \DateTime $testLastAttempted
     *
     * @return QtiUserTest
     */
    public function setTestLastAttempted($testLastAttempted)
    {
        $this->testLastAttempted = $testLastAttempted;

        return $this;
    }

    /**
     * Get testLastAttempted
     *
     * @return \DateTime
     */
    public function getTestLastAttempted()
    {
        return $this->testLastAttempted;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     *
     * @return QtiUserTest
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return integer
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set testCompletedDate
     *
     * @param \DateTime $testCompletedDate
     *
     * @return QtiUserTest
     */
    public function setTestCompletedDate($testCompletedDate)
    {
        $this->testCompletedDate = $testCompletedDate;

        return $this;
    }

    /**
     * Get testCompletedDate
     *
     * @return \DateTime
     */
    public function getTestCompletedDate()
    {
        return $this->testCompletedDate;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiUserTest
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
     * @return QtiUserTest
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
     * @return QtiUserTest
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
     * @return QtiUserTest
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
     * Set testStatus
     *
     * @param \QuizzingPlatform\Entity\QtiTestStatus $testStatus
     *
     * @return QtiUserTest
     */
    public function setTestStatus(\QuizzingPlatform\Entity\QtiTestStatus $testStatus = null)
    {
        $this->testStatus = $testStatus;

        return $this;
    }

    /**
     * Get testStatus
     *
     * @return \QuizzingPlatform\Entity\QtiTestStatus
     */
    public function getTestStatus()
    {
        return $this->testStatus;
    }

    /**
     * Set testPk
     *
     * @param \QuizzingPlatform\Entity\QtiTest $testPk
     *
     * @return QtiUserTest
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

    /**
     * Set user
     *
     * @param \QuizzingPlatform\Entity\OrgUserProfile $user
     *
     * @return QtiUserTest
     */
    public function setUser(\QuizzingPlatform\Entity\OrgUserProfile $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \QuizzingPlatform\Entity\OrgUserProfile
     */
    public function getUser()
    {
        return $this->user;
    }
}
