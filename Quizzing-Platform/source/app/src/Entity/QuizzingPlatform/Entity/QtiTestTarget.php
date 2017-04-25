<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiTestTarget
 *
 * @ORM\Table(name="qti_test_target", indexes={@ORM\Index(name="fk__quiz_target__quiz_target_type_idx", columns={"test_target_type_id"}), @ORM\Index(name="test_pk_id", columns={"test_pk_id"})})
 * @ORM\Entity
 */
class QtiTestTarget
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
     * @ORM\Column(name="status", type="smallint", nullable=true)
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
     * @var \QuizzingPlatform\Entity\QtiTestTargetType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiTestTargetType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="test_target_type_id", referencedColumnName="test_target_type_id")
     * })
     */
    private $testTargetType;

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
     * Set status
     *
     * @param integer $status
     *
     * @return QtiTestTarget
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
     * @return QtiTestTarget
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
     * @return QtiTestTarget
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
     * @return QtiTestTarget
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
     * @return QtiTestTarget
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
     * Set testTargetType
     *
     * @param \QuizzingPlatform\Entity\QtiTestTargetType $testTargetType
     *
     * @return QtiTestTarget
     */
    public function setTestTargetType(\QuizzingPlatform\Entity\QtiTestTargetType $testTargetType = null)
    {
        $this->testTargetType = $testTargetType;

        return $this;
    }

    /**
     * Get testTargetType
     *
     * @return \QuizzingPlatform\Entity\QtiTestTargetType
     */
    public function getTestTargetType()
    {
        return $this->testTargetType;
    }

    /**
     * Set testPk
     *
     * @param \QuizzingPlatform\Entity\QtiTest $testPk
     *
     * @return QtiTestTarget
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
