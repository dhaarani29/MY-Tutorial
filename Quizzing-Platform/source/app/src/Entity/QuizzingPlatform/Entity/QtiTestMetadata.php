<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiTestMetadata
 *
 * @ORM\Table(name="qti_test_metadata", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="taxonomy_id", columns={"metadata_value_id"}), @ORM\Index(name="metadata_id", columns={"metadata_id"})})
 * @ORM\Entity
 */
class QtiTestMetadata
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
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", nullable=false)
     */
    private $modifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime", nullable=false)
     */
    private $modifiedDate;

    /**
     * @var \QuizzingPlatform\Entity\CmnMetadataHierarchyValues
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnMetadataHierarchyValues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="metadata_value_id", referencedColumnName="id")
     * })
     */
    private $metadataValue;

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
     * @return QtiTestMetadata
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
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiTestMetadata
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
     * @return QtiTestMetadata
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
     * @return QtiTestMetadata
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
     * @return QtiTestMetadata
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
     * Set metadataValue
     *
     * @param \QuizzingPlatform\Entity\CmnMetadataHierarchyValues $metadataValue
     *
     * @return QtiTestMetadata
     */
    public function setMetadataValue(\QuizzingPlatform\Entity\CmnMetadataHierarchyValues $metadataValue = null)
    {
        $this->metadataValue = $metadataValue;

        return $this;
    }

    /**
     * Get metadataValue
     *
     * @return \QuizzingPlatform\Entity\CmnMetadataHierarchyValues
     */
    public function getMetadataValue()
    {
        return $this->metadataValue;
    }

    /**
     * Set metadata
     *
     * @param \QuizzingPlatform\Entity\CmnMetadata $metadata
     *
     * @return QtiTestMetadata
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
