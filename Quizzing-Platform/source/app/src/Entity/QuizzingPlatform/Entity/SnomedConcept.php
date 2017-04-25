<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SnomedConcept
 *
 * @ORM\Table(name="snomed_concept", indexes={@ORM\Index(name="idx_id", columns={"id"}), @ORM\Index(name="idx_effectivetime", columns={"effectivetime"}), @ORM\Index(name="idx_active", columns={"active"}), @ORM\Index(name="idx_moduleid", columns={"moduleid"}), @ORM\Index(name="idx_definitionstatusid", columns={"definitionstatusid"})})
 * @ORM\Entity
 */
class SnomedConcept
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="effectivetime", type="string", length=8, nullable=false)
     */
    private $effectivetime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="moduleid", type="bigint", nullable=false)
     */
    private $moduleid;

    /**
     * @var integer
     *
     * @ORM\Column(name="definitionstatusid", type="bigint", nullable=false)
     */
    private $definitionstatusid;



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
     * Set effectivetime
     *
     * @param string $effectivetime
     *
     * @return SnomedConcept
     */
    public function setEffectivetime($effectivetime)
    {
        $this->effectivetime = $effectivetime;

        return $this;
    }

    /**
     * Get effectivetime
     *
     * @return string
     */
    public function getEffectivetime()
    {
        return $this->effectivetime;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return SnomedConcept
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set moduleid
     *
     * @param integer $moduleid
     *
     * @return SnomedConcept
     */
    public function setModuleid($moduleid)
    {
        $this->moduleid = $moduleid;

        return $this;
    }

    /**
     * Get moduleid
     *
     * @return integer
     */
    public function getModuleid()
    {
        return $this->moduleid;
    }

    /**
     * Set definitionstatusid
     *
     * @param integer $definitionstatusid
     *
     * @return SnomedConcept
     */
    public function setDefinitionstatusid($definitionstatusid)
    {
        $this->definitionstatusid = $definitionstatusid;

        return $this;
    }

    /**
     * Get definitionstatusid
     *
     * @return integer
     */
    public function getDefinitionstatusid()
    {
        return $this->definitionstatusid;
    }
}
