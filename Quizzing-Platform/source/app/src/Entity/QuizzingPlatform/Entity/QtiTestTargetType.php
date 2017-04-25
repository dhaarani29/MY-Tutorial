<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiTestTargetType
 *
 * @ORM\Table(name="qti_test_target_type")
 * @ORM\Entity
 */
class QtiTestTargetType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="test_target_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $testTargetTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="test_target_type_name", type="string", length=50, nullable=false)
     */
    private $testTargetTypeName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

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
     * Get testTargetTypeId
     *
     * @return integer
     */
    public function getTestTargetTypeId()
    {
        return $this->testTargetTypeId;
    }

    /**
     * Set testTargetTypeName
     *
     * @param string $testTargetTypeName
     *
     * @return QtiTestTargetType
     */
    public function setTestTargetTypeName($testTargetTypeName)
    {
        $this->testTargetTypeName = $testTargetTypeName;

        return $this;
    }

    /**
     * Get testTargetTypeName
     *
     * @return string
     */
    public function getTestTargetTypeName()
    {
        return $this->testTargetTypeName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return QtiTestTargetType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiTestTargetType
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
     * @return QtiTestTargetType
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
     * @return QtiTestTargetType
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
     * @return QtiTestTargetType
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
