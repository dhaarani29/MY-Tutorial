<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemBankUpload
 *
 * @ORM\Table(name="qti_item_bank_upload", indexes={@ORM\Index(name="item_bank_id", columns={"item_bank_id"})})
 * @ORM\Entity
 */
class QtiItemBankUpload
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
     * @ORM\Column(name="content_type", type="string", length=1, nullable=true)
     */
    private $contentType;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_of_questions", type="integer", nullable=true)
     */
    private $noOfQuestions;

    /**
     * @var integer
     *
     * @ORM\Column(name="questions_processed", type="integer", nullable=true)
     */
    private $questionsProcessed;

    /**
     * @var integer
     *
     * @ORM\Column(name="questions_failed", type="integer", nullable=true)
     */
    private $questionsFailed;

    /**
     * @var integer
     *
     * @ORM\Column(name="job_id", type="integer", nullable=true)
     */
    private $jobId;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

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
     * @var \QuizzingPlatform\Entity\QtiItemBank
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItemBank")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_bank_id", referencedColumnName="item_bank_id")
     * })
     */
    private $itemBank;



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
     * Set contentType
     *
     * @param string $contentType
     *
     * @return QtiItemBankUpload
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return QtiItemBankUpload
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set noOfQuestions
     *
     * @param integer $noOfQuestions
     *
     * @return QtiItemBankUpload
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
     * Set questionsProcessed
     *
     * @param integer $questionsProcessed
     *
     * @return QtiItemBankUpload
     */
    public function setQuestionsProcessed($questionsProcessed)
    {
        $this->questionsProcessed = $questionsProcessed;

        return $this;
    }

    /**
     * Get questionsProcessed
     *
     * @return integer
     */
    public function getQuestionsProcessed()
    {
        return $this->questionsProcessed;
    }

    /**
     * Set questionsFailed
     *
     * @param integer $questionsFailed
     *
     * @return QtiItemBankUpload
     */
    public function setQuestionsFailed($questionsFailed)
    {
        $this->questionsFailed = $questionsFailed;

        return $this;
    }

    /**
     * Get questionsFailed
     *
     * @return integer
     */
    public function getQuestionsFailed()
    {
        return $this->questionsFailed;
    }

    /**
     * Set jobId
     *
     * @param integer $jobId
     *
     * @return QtiItemBankUpload
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * Get jobId
     *
     * @return integer
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return QtiItemBankUpload
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemBankUpload
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
     * @return QtiItemBankUpload
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
     * @return QtiItemBankUpload
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
     * @return QtiItemBankUpload
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
     * Set itemBank
     *
     * @param \QuizzingPlatform\Entity\QtiItemBank $itemBank
     *
     * @return QtiItemBankUpload
     */
    public function setItemBank(\QuizzingPlatform\Entity\QtiItemBank $itemBank = null)
    {
        $this->itemBank = $itemBank;

        return $this;
    }

    /**
     * Get itemBank
     *
     * @return \QuizzingPlatform\Entity\QtiItemBank
     */
    public function getItemBank()
    {
        return $this->itemBank;
    }
}
