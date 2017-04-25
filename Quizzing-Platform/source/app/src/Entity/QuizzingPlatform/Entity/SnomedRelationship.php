<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SnomedRelationship
 *
 * @ORM\Table(name="snomed_relationship", indexes={@ORM\Index(name="idx_id", columns={"id"}), @ORM\Index(name="idx_effectivetime", columns={"effectivetime"}), @ORM\Index(name="idx_active", columns={"active"}), @ORM\Index(name="idx_moduleid", columns={"moduleid"}), @ORM\Index(name="idx_sourceid", columns={"sourceid"}), @ORM\Index(name="idx_destinationid", columns={"destinationid"}), @ORM\Index(name="idx_relationshipgroup", columns={"relationshipgroup"}), @ORM\Index(name="idx_typeid", columns={"typeid"}), @ORM\Index(name="idx_characteristictypeid", columns={"characteristictypeid"}), @ORM\Index(name="idx_modifierid", columns={"modifierid"})})
 * @ORM\Entity
 */
class SnomedRelationship
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
     * @ORM\Column(name="relationshipgroup", type="integer", nullable=false)
     */
    private $relationshipgroup;

    /**
     * @var integer
     *
     * @ORM\Column(name="typeid", type="bigint", nullable=false)
     */
    private $typeid;

    /**
     * @var integer
     *
     * @ORM\Column(name="characteristictypeid", type="bigint", nullable=false)
     */
    private $characteristictypeid;

    /**
     * @var integer
     *
     * @ORM\Column(name="modifierid", type="bigint", nullable=false)
     */
    private $modifierid;

    /**
     * @var \QuizzingPlatform\Entity\SnomedConcept
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\SnomedConcept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sourceid", referencedColumnName="id")
     * })
     */
    private $sourceid;

    /**
     * @var \QuizzingPlatform\Entity\SnomedConcept
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\SnomedConcept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="destinationid", referencedColumnName="id")
     * })
     */
    private $destinationid;



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
     * @return SnomedRelationship
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
     * @return SnomedRelationship
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
     * @return SnomedRelationship
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
     * Set relationshipgroup
     *
     * @param integer $relationshipgroup
     *
     * @return SnomedRelationship
     */
    public function setRelationshipgroup($relationshipgroup)
    {
        $this->relationshipgroup = $relationshipgroup;

        return $this;
    }

    /**
     * Get relationshipgroup
     *
     * @return integer
     */
    public function getRelationshipgroup()
    {
        return $this->relationshipgroup;
    }

    /**
     * Set typeid
     *
     * @param integer $typeid
     *
     * @return SnomedRelationship
     */
    public function setTypeid($typeid)
    {
        $this->typeid = $typeid;

        return $this;
    }

    /**
     * Get typeid
     *
     * @return integer
     */
    public function getTypeid()
    {
        return $this->typeid;
    }

    /**
     * Set characteristictypeid
     *
     * @param integer $characteristictypeid
     *
     * @return SnomedRelationship
     */
    public function setCharacteristictypeid($characteristictypeid)
    {
        $this->characteristictypeid = $characteristictypeid;

        return $this;
    }

    /**
     * Get characteristictypeid
     *
     * @return integer
     */
    public function getCharacteristictypeid()
    {
        return $this->characteristictypeid;
    }

    /**
     * Set modifierid
     *
     * @param integer $modifierid
     *
     * @return SnomedRelationship
     */
    public function setModifierid($modifierid)
    {
        $this->modifierid = $modifierid;

        return $this;
    }

    /**
     * Get modifierid
     *
     * @return integer
     */
    public function getModifierid()
    {
        return $this->modifierid;
    }

    /**
     * Set sourceid
     *
     * @param \QuizzingPlatform\Entity\SnomedConcept $sourceid
     *
     * @return SnomedRelationship
     */
    public function setSourceid(\QuizzingPlatform\Entity\SnomedConcept $sourceid = null)
    {
        $this->sourceid = $sourceid;

        return $this;
    }

    /**
     * Get sourceid
     *
     * @return \QuizzingPlatform\Entity\SnomedConcept
     */
    public function getSourceid()
    {
        return $this->sourceid;
    }

    /**
     * Set destinationid
     *
     * @param \QuizzingPlatform\Entity\SnomedConcept $destinationid
     *
     * @return SnomedRelationship
     */
    public function setDestinationid(\QuizzingPlatform\Entity\SnomedConcept $destinationid = null)
    {
        $this->destinationid = $destinationid;

        return $this;
    }

    /**
     * Get destinationid
     *
     * @return \QuizzingPlatform\Entity\SnomedConcept
     */
    public function getDestinationid()
    {
        return $this->destinationid;
    }
}
