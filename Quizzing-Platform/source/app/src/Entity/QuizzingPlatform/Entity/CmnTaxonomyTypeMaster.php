<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnTaxonomyTypeMaster
 *
 * @ORM\Table(name="cmn_taxonomy_type_master", indexes={@ORM\Index(name="taxanomy_type_id", columns={"taxonomy_type_id"})})
 * @ORM\Entity
 */
class CmnTaxonomyTypeMaster
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="taxonomy_type_id", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $taxonomyTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="taxonomy_type_name", type="string", length=50, nullable=false)
     */
    private $taxonomyTypeName;

    /**
     * @var string
     *
     * @ORM\Column(name="taxonomy_type_desc", type="string", length=100, nullable=false)
     */
    private $taxonomyTypeDesc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;



    /**
     * Get taxonomyTypeId
     *
     * @return boolean
     */
    public function getTaxonomyTypeId()
    {
        return $this->taxonomyTypeId;
    }

    /**
     * Set taxonomyTypeName
     *
     * @param string $taxonomyTypeName
     *
     * @return CmnTaxonomyTypeMaster
     */
    public function setTaxonomyTypeName($taxonomyTypeName)
    {
        $this->taxonomyTypeName = $taxonomyTypeName;

        return $this;
    }

    /**
     * Get taxonomyTypeName
     *
     * @return string
     */
    public function getTaxonomyTypeName()
    {
        return $this->taxonomyTypeName;
    }

    /**
     * Set taxonomyTypeDesc
     *
     * @param string $taxonomyTypeDesc
     *
     * @return CmnTaxonomyTypeMaster
     */
    public function setTaxonomyTypeDesc($taxonomyTypeDesc)
    {
        $this->taxonomyTypeDesc = $taxonomyTypeDesc;

        return $this;
    }

    /**
     * Get taxonomyTypeDesc
     *
     * @return string
     */
    public function getTaxonomyTypeDesc()
    {
        return $this->taxonomyTypeDesc;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return CmnTaxonomyTypeMaster
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
}
