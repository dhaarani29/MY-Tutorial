<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnResourceMetadata
 *
 * @ORM\Table(name="cmn_resource_metadata", indexes={@ORM\Index(name="resouce_id", columns={"resource_id"}), @ORM\Index(name="metadata_id", columns={"metadata_id"}), @ORM\Index(name="resource_type_id", columns={"resource_type_id"})})
 * @ORM\Entity
 */
class CmnResourceMetadata
{
    /**
     * @var integer
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sequence;

    /**
     * @var integer
     *
     * @ORM\Column(name="resource_id", type="integer", nullable=true)
     */
    private $resourceId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", length=65535, nullable=true)
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
     * @var \QuizzingPlatform\Entity\CmnResourceType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnResourceType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resource_type_id", referencedColumnName="resource_type_id")
     * })
     */
    private $resourceType;

    /**
     * @var \QuizzingPlatform\Entity\CmnMetadata
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnMetadata")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="metadata_id", referencedColumnName="metadata_id")
     * })
     */
    private $metadata;



    /**
     * Get sequence
     *
     * @return integer
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set resourceId
     *
     * @param integer $resourceId
     *
     * @return CmnResourceMetadata
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * Get resourceId
     *
     * @return integer
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return CmnResourceMetadata
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
     * @return CmnResourceMetadata
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
     * @return CmnResourceMetadata
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
     * @return CmnResourceMetadata
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
     * @return CmnResourceMetadata
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
     * Set resourceType
     *
     * @param \QuizzingPlatform\Entity\CmnResourceType $resourceType
     *
     * @return CmnResourceMetadata
     */
    public function setResourceType(\QuizzingPlatform\Entity\CmnResourceType $resourceType = null)
    {
        $this->resourceType = $resourceType;

        return $this;
    }

    /**
     * Get resourceType
     *
     * @return \QuizzingPlatform\Entity\CmnResourceType
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * Set metadata
     *
     * @param \QuizzingPlatform\Entity\CmnMetadata $metadata
     *
     * @return CmnResourceMetadata
     */
    public function setMetadata(\QuizzingPlatform\Entity\CmnMetadata $metadata = null)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return \QuizzingPlatform\Entity\CmnMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
