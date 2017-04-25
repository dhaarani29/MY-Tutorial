<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SnomedDescription
 *
 * @ORM\Table(name="snomed_description", indexes={@ORM\Index(name="idx_id", columns={"id"}), @ORM\Index(name="idx_effectivetime", columns={"effectivetime"}), @ORM\Index(name="idx_active", columns={"active"}), @ORM\Index(name="idx_moduleid", columns={"moduleid"}), @ORM\Index(name="idx_conceptid", columns={"conceptid"}), @ORM\Index(name="idx_languagecode", columns={"languagecode"}), @ORM\Index(name="idx_typeid", columns={"typeid"}), @ORM\Index(name="idx_casesignificanceid", columns={"casesignificanceid"})})
 * @ORM\Entity
 */
class SnomedDescription
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
     * @var string
     *
     * @ORM\Column(name="languagecode", type="string", length=2, nullable=false)
     */
    private $languagecode;

    /**
     * @var integer
     *
     * @ORM\Column(name="typeid", type="bigint", nullable=false)
     */
    private $typeid;

    /**
     * @var string
     *
     * @ORM\Column(name="term", type="string", length=255, nullable=false)
     */
    private $term;

    /**
     * @var integer
     *
     * @ORM\Column(name="casesignificanceid", type="bigint", nullable=false)
     */
    private $casesignificanceid;

    /**
     * @var \QuizzingPlatform\Entity\SnomedConcept
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\SnomedConcept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="conceptid", referencedColumnName="id")
     * })
     */
    private $conceptid;



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
     * @return SnomedDescription
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
     * @return SnomedDescription
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
     * @return SnomedDescription
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
     * Set languagecode
     *
     * @param string $languagecode
     *
     * @return SnomedDescription
     */
    public function setLanguagecode($languagecode)
    {
        $this->languagecode = $languagecode;

        return $this;
    }

    /**
     * Get languagecode
     *
     * @return string
     */
    public function getLanguagecode()
    {
        return $this->languagecode;
    }

    /**
     * Set typeid
     *
     * @param integer $typeid
     *
     * @return SnomedDescription
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
     * Set term
     *
     * @param string $term
     *
     * @return SnomedDescription
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set casesignificanceid
     *
     * @param integer $casesignificanceid
     *
     * @return SnomedDescription
     */
    public function setCasesignificanceid($casesignificanceid)
    {
        $this->casesignificanceid = $casesignificanceid;

        return $this;
    }

    /**
     * Get casesignificanceid
     *
     * @return integer
     */
    public function getCasesignificanceid()
    {
        return $this->casesignificanceid;
    }

    /**
     * Set conceptid
     *
     * @param \QuizzingPlatform\Entity\SnomedConcept $conceptid
     *
     * @return SnomedDescription
     */
    public function setConceptid(\QuizzingPlatform\Entity\SnomedConcept $conceptid = null)
    {
        $this->conceptid = $conceptid;

        return $this;
    }

    /**
     * Get conceptid
     *
     * @return \QuizzingPlatform\Entity\SnomedConcept
     */
    public function getConceptid()
    {
        return $this->conceptid;
    }
}
