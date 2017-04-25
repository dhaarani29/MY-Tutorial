<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiRemediationLinkType
 *
 * @ORM\Table(name="qti_remediation_link_type")
 * @ORM\Entity
 */
class QtiRemediationLinkType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="remediation_link_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $remediationLinkTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="remediation_link_type_name", type="string", length=50, nullable=false)
     */
    private $remediationLinkTypeName;

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
     * Get remediationLinkTypeId
     *
     * @return integer
     */
    public function getRemediationLinkTypeId()
    {
        return $this->remediationLinkTypeId;
    }

    /**
     * Set remediationLinkTypeName
     *
     * @param string $remediationLinkTypeName
     *
     * @return QtiRemediationLinkType
     */
    public function setRemediationLinkTypeName($remediationLinkTypeName)
    {
        $this->remediationLinkTypeName = $remediationLinkTypeName;

        return $this;
    }

    /**
     * Get remediationLinkTypeName
     *
     * @return string
     */
    public function getRemediationLinkTypeName()
    {
        return $this->remediationLinkTypeName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return QtiRemediationLinkType
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
     * @return QtiRemediationLinkType
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
     * @return QtiRemediationLinkType
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
     * @return QtiRemediationLinkType
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
     * @return QtiRemediationLinkType
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
