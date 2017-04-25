<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnMetadataTaxonomyMapping
 *
 * @ORM\Table(name="cmn_metadata_taxonomy_mapping", indexes={@ORM\Index(name="source_taxanomy_id", columns={"source_taxonomy_id", "source_taxonomy_type_id", "destination_taxonomy_id", "destination_taxonomy_type_id"}), @ORM\Index(name="id", columns={"id"}), @ORM\Index(name="cmn_metadata_taxanomy_mapping_ibfk_2", columns={"destination_taxonomy_id"}), @ORM\Index(name="cmn_metadata_taxanomy_mapping_ibfk_3", columns={"source_taxonomy_type_id"}), @ORM\Index(name="cmn_metadata_taxanomy_mapping_ibfk_4", columns={"destination_taxonomy_type_id"}), @ORM\Index(name="IDX_CAF85DA2A928B656", columns={"source_taxonomy_id"})})
 * @ORM\Entity
 */
class CmnMetadataTaxonomyMapping
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \QuizzingPlatform\Entity\CmnMetadataHierarchyValues
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnMetadataHierarchyValues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source_taxonomy_id", referencedColumnName="id")
     * })
     */
    private $sourceTaxonomy;

    /**
     * @var \QuizzingPlatform\Entity\SnomedConcept
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\SnomedConcept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="destination_taxonomy_id", referencedColumnName="id")
     * })
     */
    private $destinationTaxonomy;

    /**
     * @var \QuizzingPlatform\Entity\CmnTaxonomyTypeMaster
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnTaxonomyTypeMaster")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source_taxonomy_type_id", referencedColumnName="taxonomy_type_id")
     * })
     */
    private $sourceTaxonomyType;

    /**
     * @var \QuizzingPlatform\Entity\CmnTaxonomyTypeMaster
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnTaxonomyTypeMaster")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="destination_taxonomy_type_id", referencedColumnName="taxonomy_type_id")
     * })
     */
    private $destinationTaxonomyType;



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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return CmnMetadataTaxonomyMapping
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
     * Set status
     *
     * @param boolean $status
     *
     * @return CmnMetadataTaxonomyMapping
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sourceTaxonomy
     *
     * @param \QuizzingPlatform\Entity\CmnMetadataHierarchyValues $sourceTaxonomy
     *
     * @return CmnMetadataTaxonomyMapping
     */
    public function setSourceTaxonomy(\QuizzingPlatform\Entity\CmnMetadataHierarchyValues $sourceTaxonomy = null)
    {
        $this->sourceTaxonomy = $sourceTaxonomy;

        return $this;
    }

    /**
     * Get sourceTaxonomy
     *
     * @return \QuizzingPlatform\Entity\CmnMetadataHierarchyValues
     */
    public function getSourceTaxonomy()
    {
        return $this->sourceTaxonomy;
    }

    /**
     * Set destinationTaxonomy
     *
     * @param \QuizzingPlatform\Entity\SnomedConcept $destinationTaxonomy
     *
     * @return CmnMetadataTaxonomyMapping
     */
    public function setDestinationTaxonomy(\QuizzingPlatform\Entity\SnomedConcept $destinationTaxonomy = null)
    {
        $this->destinationTaxonomy = $destinationTaxonomy;

        return $this;
    }

    /**
     * Get destinationTaxonomy
     *
     * @return \QuizzingPlatform\Entity\SnomedConcept
     */
    public function getDestinationTaxonomy()
    {
        return $this->destinationTaxonomy;
    }

    /**
     * Set sourceTaxonomyType
     *
     * @param \QuizzingPlatform\Entity\CmnTaxonomyTypeMaster $sourceTaxonomyType
     *
     * @return CmnMetadataTaxonomyMapping
     */
    public function setSourceTaxonomyType(\QuizzingPlatform\Entity\CmnTaxonomyTypeMaster $sourceTaxonomyType = null)
    {
        $this->sourceTaxonomyType = $sourceTaxonomyType;

        return $this;
    }

    /**
     * Get sourceTaxonomyType
     *
     * @return \QuizzingPlatform\Entity\CmnTaxonomyTypeMaster
     */
    public function getSourceTaxonomyType()
    {
        return $this->sourceTaxonomyType;
    }

    /**
     * Set destinationTaxonomyType
     *
     * @param \QuizzingPlatform\Entity\CmnTaxonomyTypeMaster $destinationTaxonomyType
     *
     * @return CmnMetadataTaxonomyMapping
     */
    public function setDestinationTaxonomyType(\QuizzingPlatform\Entity\CmnTaxonomyTypeMaster $destinationTaxonomyType = null)
    {
        $this->destinationTaxonomyType = $destinationTaxonomyType;

        return $this;
    }

    /**
     * Get destinationTaxonomyType
     *
     * @return \QuizzingPlatform\Entity\CmnTaxonomyTypeMaster
     */
    public function getDestinationTaxonomyType()
    {
        return $this->destinationTaxonomyType;
    }
}
