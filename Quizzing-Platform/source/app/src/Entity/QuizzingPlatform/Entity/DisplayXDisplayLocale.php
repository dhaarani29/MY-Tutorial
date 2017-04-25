<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisplayXDisplayLocale
 *
 * @ORM\Table(name="display_x_display_locale", indexes={@ORM\Index(name="IDX_589F8B0CE559DFD1", columns={"locale_id"})})
 * @ORM\Entity
 */
class DisplayXDisplayLocale
{
    /**
     * @var string
     *
     * @ORM\Column(name="locale_code", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $localeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="display_locale_name", type="string", length=50, nullable=false)
     */
    private $displayLocaleName;

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
     * @var \QuizzingPlatform\Entity\DisplayLocale
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="QuizzingPlatform\Entity\DisplayLocale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="locale_id", referencedColumnName="locale_id")
     * })
     */
    private $locale;



    /**
     * Set localeCode
     *
     * @param string $localeCode
     *
     * @return DisplayXDisplayLocale
     */
    public function setLocaleCode($localeCode)
    {
        $this->localeCode = $localeCode;

        return $this;
    }

    /**
     * Get localeCode
     *
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    /**
     * Set displayLocaleName
     *
     * @param string $displayLocaleName
     *
     * @return DisplayXDisplayLocale
     */
    public function setDisplayLocaleName($displayLocaleName)
    {
        $this->displayLocaleName = $displayLocaleName;

        return $this;
    }

    /**
     * Get displayLocaleName
     *
     * @return string
     */
    public function getDisplayLocaleName()
    {
        return $this->displayLocaleName;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return DisplayXDisplayLocale
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
     * @return DisplayXDisplayLocale
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
     * @return DisplayXDisplayLocale
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
     * @return DisplayXDisplayLocale
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
     * Set locale
     *
     * @param \QuizzingPlatform\Entity\DisplayLocale $locale
     *
     * @return DisplayXDisplayLocale
     */
    public function setLocale(\QuizzingPlatform\Entity\DisplayLocale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return \QuizzingPlatform\Entity\DisplayLocale
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
