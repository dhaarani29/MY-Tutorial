<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisplayLocale
 *
 * @ORM\Table(name="display_locale", uniqueConstraints={@ORM\UniqueConstraint(name="locale_code_UNIQUE", columns={"locale_code"})})
 * @ORM\Entity
 */
class DisplayLocale
{
    /**
     * @var integer
     *
     * @ORM\Column(name="locale_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $localeId;

    /**
     * @var string
     *
     * @ORM\Column(name="locale_code", type="string", length=10, nullable=true)
     */
    private $localeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="locale_name", type="string", length=45, nullable=true)
     */
    private $localeName;

    /**
     * @var integer
     *
     * @ORM\Column(name="description", type="integer", nullable=true)
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
     * Get localeId
     *
     * @return integer
     */
    public function getLocaleId()
    {
        return $this->localeId;
    }

    /**
     * Set localeCode
     *
     * @param string $localeCode
     *
     * @return DisplayLocale
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
     * Set localeName
     *
     * @param string $localeName
     *
     * @return DisplayLocale
     */
    public function setLocaleName($localeName)
    {
        $this->localeName = $localeName;

        return $this;
    }

    /**
     * Get localeName
     *
     * @return string
     */
    public function getLocaleName()
    {
        return $this->localeName;
    }

    /**
     * Set description
     *
     * @param integer $description
     *
     * @return DisplayLocale
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return integer
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
     * @return DisplayLocale
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
     * @return DisplayLocale
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
     * @return DisplayLocale
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
     * @return DisplayLocale
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
