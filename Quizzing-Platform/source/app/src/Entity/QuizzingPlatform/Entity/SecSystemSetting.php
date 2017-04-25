<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SecSystemSetting
 *
 * @ORM\Table(name="sec_system_setting")
 * @ORM\Entity
 */
class SecSystemSetting
{
    /**
     * @var integer
     *
     * @ORM\Column(name="system_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $systemId;

    /**
     * @var string
     *
     * @ORM\Column(name="system_key", type="string", length=50, nullable=true)
     */
    private $systemKey;

    /**
     * @var string
     *
     * @ORM\Column(name="system_value", type="text", length=65535, nullable=true)
     */
    private $systemValue;

    /**
     * @var string
     *
     * @ORM\Column(name="key_definition", type="string", length=255, nullable=false)
     */
    private $keyDefinition;

    /**
     * @var boolean
     *
     * @ORM\Column(name="access_flag", type="boolean", nullable=false)
     */
    private $accessFlag;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy = '1';

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
     * @var string
     *
     * @ORM\Column(name="portal", type="string", length=4, nullable=false)
     */
    private $portal;



    /**
     * Get systemId
     *
     * @return integer
     */
    public function getSystemId()
    {
        return $this->systemId;
    }

    /**
     * Set systemKey
     *
     * @param string $systemKey
     *
     * @return SecSystemSetting
     */
    public function setSystemKey($systemKey)
    {
        $this->systemKey = $systemKey;

        return $this;
    }

    /**
     * Get systemKey
     *
     * @return string
     */
    public function getSystemKey()
    {
        return $this->systemKey;
    }

    /**
     * Set systemValue
     *
     * @param string $systemValue
     *
     * @return SecSystemSetting
     */
    public function setSystemValue($systemValue)
    {
        $this->systemValue = $systemValue;

        return $this;
    }

    /**
     * Get systemValue
     *
     * @return string
     */
    public function getSystemValue()
    {
        return $this->systemValue;
    }

    /**
     * Set keyDefinition
     *
     * @param string $keyDefinition
     *
     * @return SecSystemSetting
     */
    public function setKeyDefinition($keyDefinition)
    {
        $this->keyDefinition = $keyDefinition;

        return $this;
    }

    /**
     * Get keyDefinition
     *
     * @return string
     */
    public function getKeyDefinition()
    {
        return $this->keyDefinition;
    }

    /**
     * Set accessFlag
     *
     * @param boolean $accessFlag
     *
     * @return SecSystemSetting
     */
    public function setAccessFlag($accessFlag)
    {
        $this->accessFlag = $accessFlag;

        return $this;
    }

    /**
     * Get accessFlag
     *
     * @return boolean
     */
    public function getAccessFlag()
    {
        return $this->accessFlag;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return SecSystemSetting
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
     * @return SecSystemSetting
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
     * @return SecSystemSetting
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
     * @return SecSystemSetting
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
     * Set portal
     *
     * @param string $portal
     *
     * @return SecSystemSetting
     */
    public function setPortal($portal)
    {
        $this->portal = $portal;

        return $this;
    }

    /**
     * Get portal
     *
     * @return string
     */
    public function getPortal()
    {
        return $this->portal;
    }
}
