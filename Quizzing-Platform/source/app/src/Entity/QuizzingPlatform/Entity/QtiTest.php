<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiTest
 *
 * @ORM\Table(name="qti_test", indexes={@ORM\Index(name="fk__quiz__quiz_type_idx", columns={"test_type_id"}), @ORM\Index(name="client_id", columns={"client_id"}), @ORM\Index(name="test_id", columns={"test_id"})})
 * @ORM\Entity
 */
class QtiTest
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
     * @ORM\Column(name="test_id", type="integer", nullable=false)
     */
    private $testId;

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var boolean
     *
     * @ORM\Column(name="test_mode", type="boolean", nullable=true)
     */
    private $testMode;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_of_questions", type="integer", nullable=true)
     */
    private $noOfQuestions;

    /**
     * @var integer
     *
     * @ORM\Column(name="general_test", type="smallint", nullable=false)
     */
    private $generalTest = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="enable_feedback", type="boolean", nullable=true)
     */
    private $enableFeedback;

    /**
     * @var integer
     *
     * @ORM\Column(name="navigation_type", type="integer", nullable=true)
     */
    private $navigationType;

    /**
     * @var integer
     *
     * @ORM\Column(name="time_limit", type="integer", nullable=true)
     */
    private $timeLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_time_limit", type="integer", nullable=true)
     */
    private $itemTimeLimit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="override_time_limit", type="boolean", nullable=true)
     */
    private $overrideTimeLimit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="choose_question", type="boolean", nullable=true)
     */
    private $chooseQuestion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="randomize_item", type="boolean", nullable=true)
     */
    private $randomizeItem;

    /**
     * @var boolean
     *
     * @ORM\Column(name="randomize_answer", type="boolean", nullable=true)
     */
    private $randomizeAnswer;

    /**
     * @var string
     *
     * @ORM\Column(name="test_feedback", type="string", length=255, nullable=true)
     */
    private $testFeedback;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effective_date_from", type="datetime", nullable=true)
     */
    private $effectiveDateFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effective_date_to", type="datetime", nullable=true)
     */
    private $effectiveDateTo;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_deleted", type="smallint", nullable=true)
     */
    private $isDeleted;

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
     * @var \QuizzingPlatform\Entity\QtiTestType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiTestType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="test_type_id", referencedColumnName="test_type_id")
     * })
     */
    private $testType;

    /**
     * @var \QuizzingPlatform\Entity\CmnClient
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnClient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="client_id")
     * })
     */
    private $client;



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
     * Set testId
     *
     * @param integer $testId
     *
     * @return QtiTest
     */
    public function setTestId($testId)
    {
        $this->testId = $testId;

        return $this;
    }

    /**
     * Get testId
     *
     * @return integer
     */
    public function getTestId()
    {
        return $this->testId;
    }

    /**
     * Set version
     *
     * @param integer $version
     *
     * @return QtiTest
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return QtiTest
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return QtiTest
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
     * Set testMode
     *
     * @param boolean $testMode
     *
     * @return QtiTest
     */
    public function setTestMode($testMode)
    {
        $this->testMode = $testMode;

        return $this;
    }

    /**
     * Get testMode
     *
     * @return boolean
     */
    public function getTestMode()
    {
        return $this->testMode;
    }

    /**
     * Set noOfQuestions
     *
     * @param integer $noOfQuestions
     *
     * @return QtiTest
     */
    public function setNoOfQuestions($noOfQuestions)
    {
        $this->noOfQuestions = $noOfQuestions;

        return $this;
    }

    /**
     * Get noOfQuestions
     *
     * @return integer
     */
    public function getNoOfQuestions()
    {
        return $this->noOfQuestions;
    }

    /**
     * Set generalTest
     *
     * @param integer $generalTest
     *
     * @return QtiTest
     */
    public function setGeneralTest($generalTest)
    {
        $this->generalTest = $generalTest;

        return $this;
    }

    /**
     * Get generalTest
     *
     * @return integer
     */
    public function getGeneralTest()
    {
        return $this->generalTest;
    }

    /**
     * Set enableFeedback
     *
     * @param boolean $enableFeedback
     *
     * @return QtiTest
     */
    public function setEnableFeedback($enableFeedback)
    {
        $this->enableFeedback = $enableFeedback;

        return $this;
    }

    /**
     * Get enableFeedback
     *
     * @return boolean
     */
    public function getEnableFeedback()
    {
        return $this->enableFeedback;
    }

    /**
     * Set navigationType
     *
     * @param integer $navigationType
     *
     * @return QtiTest
     */
    public function setNavigationType($navigationType)
    {
        $this->navigationType = $navigationType;

        return $this;
    }

    /**
     * Get navigationType
     *
     * @return integer
     */
    public function getNavigationType()
    {
        return $this->navigationType;
    }

    /**
     * Set timeLimit
     *
     * @param integer $timeLimit
     *
     * @return QtiTest
     */
    public function setTimeLimit($timeLimit)
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    /**
     * Get timeLimit
     *
     * @return integer
     */
    public function getTimeLimit()
    {
        return $this->timeLimit;
    }

    /**
     * Set itemTimeLimit
     *
     * @param integer $itemTimeLimit
     *
     * @return QtiTest
     */
    public function setItemTimeLimit($itemTimeLimit)
    {
        $this->itemTimeLimit = $itemTimeLimit;

        return $this;
    }

    /**
     * Get itemTimeLimit
     *
     * @return integer
     */
    public function getItemTimeLimit()
    {
        return $this->itemTimeLimit;
    }

    /**
     * Set overrideTimeLimit
     *
     * @param boolean $overrideTimeLimit
     *
     * @return QtiTest
     */
    public function setOverrideTimeLimit($overrideTimeLimit)
    {
        $this->overrideTimeLimit = $overrideTimeLimit;

        return $this;
    }

    /**
     * Get overrideTimeLimit
     *
     * @return boolean
     */
    public function getOverrideTimeLimit()
    {
        return $this->overrideTimeLimit;
    }

    /**
     * Set chooseQuestion
     *
     * @param boolean $chooseQuestion
     *
     * @return QtiTest
     */
    public function setChooseQuestion($chooseQuestion)
    {
        $this->chooseQuestion = $chooseQuestion;

        return $this;
    }

    /**
     * Get chooseQuestion
     *
     * @return boolean
     */
    public function getChooseQuestion()
    {
        return $this->chooseQuestion;
    }

    /**
     * Set randomizeItem
     *
     * @param boolean $randomizeItem
     *
     * @return QtiTest
     */
    public function setRandomizeItem($randomizeItem)
    {
        $this->randomizeItem = $randomizeItem;

        return $this;
    }

    /**
     * Get randomizeItem
     *
     * @return boolean
     */
    public function getRandomizeItem()
    {
        return $this->randomizeItem;
    }

    /**
     * Set randomizeAnswer
     *
     * @param boolean $randomizeAnswer
     *
     * @return QtiTest
     */
    public function setRandomizeAnswer($randomizeAnswer)
    {
        $this->randomizeAnswer = $randomizeAnswer;

        return $this;
    }

    /**
     * Get randomizeAnswer
     *
     * @return boolean
     */
    public function getRandomizeAnswer()
    {
        return $this->randomizeAnswer;
    }

    /**
     * Set testFeedback
     *
     * @param string $testFeedback
     *
     * @return QtiTest
     */
    public function setTestFeedback($testFeedback)
    {
        $this->testFeedback = $testFeedback;

        return $this;
    }

    /**
     * Get testFeedback
     *
     * @return string
     */
    public function getTestFeedback()
    {
        return $this->testFeedback;
    }

    /**
     * Set effectiveDateFrom
     *
     * @param \DateTime $effectiveDateFrom
     *
     * @return QtiTest
     */
    public function setEffectiveDateFrom($effectiveDateFrom)
    {
        $this->effectiveDateFrom = $effectiveDateFrom;

        return $this;
    }

    /**
     * Get effectiveDateFrom
     *
     * @return \DateTime
     */
    public function getEffectiveDateFrom()
    {
        return $this->effectiveDateFrom;
    }

    /**
     * Set effectiveDateTo
     *
     * @param \DateTime $effectiveDateTo
     *
     * @return QtiTest
     */
    public function setEffectiveDateTo($effectiveDateTo)
    {
        $this->effectiveDateTo = $effectiveDateTo;

        return $this;
    }

    /**
     * Get effectiveDateTo
     *
     * @return \DateTime
     */
    public function getEffectiveDateTo()
    {
        return $this->effectiveDateTo;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     *
     * @return QtiTest
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
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiTest
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
     * @return QtiTest
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
     * @return QtiTest
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
     * @return QtiTest
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
     * Set testType
     *
     * @param \QuizzingPlatform\Entity\QtiTestType $testType
     *
     * @return QtiTest
     */
    public function setTestType(\QuizzingPlatform\Entity\QtiTestType $testType = null)
    {
        $this->testType = $testType;

        return $this;
    }

    /**
     * Get testType
     *
     * @return \QuizzingPlatform\Entity\QtiTestType
     */
    public function getTestType()
    {
        return $this->testType;
    }

    /**
     * Set client
     *
     * @param \QuizzingPlatform\Entity\CmnClient $client
     *
     * @return QtiTest
     */
    public function setClient(\QuizzingPlatform\Entity\CmnClient $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \QuizzingPlatform\Entity\CmnClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
