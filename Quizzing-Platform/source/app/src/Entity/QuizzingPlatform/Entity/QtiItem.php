<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItem
 *
 * @ORM\Table(name="qti_item", indexes={@ORM\Index(name="fk_question_question_type_idx", columns={"item_type_id"}), @ORM\Index(name="fk__question__status_idx", columns={"status_id"}), @ORM\Index(name="item_id__version", columns={"item_id", "version"}), @ORM\Index(name="question_identifier_UNIQUE", columns={"qti_identifier"}), @ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class QtiItem
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
     * @ORM\Column(name="item_id", type="integer", nullable=false)
     */
    private $itemId;

    /**
     * @var string
     *
     * @ORM\Column(name="qti_identifier", type="string", length=50, nullable=false)
     */
    private $qtiIdentifier;

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="prompt_text", type="text", length=65535, nullable=true)
     */
    private $promptText;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=50, nullable=false)
     */
    private $language = 'en-US';

    /**
     * @var boolean
     *
     * @ORM\Column(name="time_dependant", type="boolean", nullable=true)
     */
    private $timeDependant;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_time_limit", type="integer", nullable=true)
     */
    private $maxTimeLimit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="adaptive", type="boolean", nullable=true)
     */
    private $adaptive;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    /**
     * @var integer
     *
     * @ORM\Column(name="difficulty", type="integer", nullable=true)
     */
    private $difficulty;

    /**
     * @var string
     *
     * @ORM\Column(name="tool_name", type="string", length=50, nullable=true)
     */
    private $toolName;

    /**
     * @var string
     *
     * @ORM\Column(name="tool_version", type="string", length=50, nullable=true)
     */
    private $toolVersion;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_deleted", type="smallint", nullable=false)
     */
    private $isDeleted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parent", type="boolean", nullable=false)
     */
    private $parent = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="online", type="smallint", nullable=false)
     */
    private $online = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effective_date_from", type="datetime", nullable=true)
     */
    private $effectiveDateFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effective_date_to", type="datetime", nullable=true)
     */
    private $effectiveDateTo;

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
     * @var \QuizzingPlatform\Entity\QtiStatus
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="status_id")
     * })
     */
    private $status;

    /**
     * @var \QuizzingPlatform\Entity\QtiItemType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItemType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_type_id", referencedColumnName="item_type_id")
     * })
     */
    private $itemType;



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
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return QtiItem
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return integer
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set qtiIdentifier
     *
     * @param string $qtiIdentifier
     *
     * @return QtiItem
     */
    public function setQtiIdentifier($qtiIdentifier)
    {
        $this->qtiIdentifier = $qtiIdentifier;

        return $this;
    }

    /**
     * Get qtiIdentifier
     *
     * @return string
     */
    public function getQtiIdentifier()
    {
        return $this->qtiIdentifier;
    }

    /**
     * Set version
     *
     * @param integer $version
     *
     * @return QtiItem
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return QtiItem
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set promptText
     *
     * @param string $promptText
     *
     * @return QtiItem
     */
    public function setPromptText($promptText)
    {
        $this->promptText = $promptText;

        return $this;
    }

    /**
     * Get promptText
     *
     * @return string
     */
    public function getPromptText()
    {
        return $this->promptText;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return QtiItem
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set timeDependant
     *
     * @param boolean $timeDependant
     *
     * @return QtiItem
     */
    public function setTimeDependant($timeDependant)
    {
        $this->timeDependant = $timeDependant;

        return $this;
    }

    /**
     * Get timeDependant
     *
     * @return boolean
     */
    public function getTimeDependant()
    {
        return $this->timeDependant;
    }

    /**
     * Set maxTimeLimit
     *
     * @param integer $maxTimeLimit
     *
     * @return QtiItem
     */
    public function setMaxTimeLimit($maxTimeLimit)
    {
        $this->maxTimeLimit = $maxTimeLimit;

        return $this;
    }

    /**
     * Get maxTimeLimit
     *
     * @return integer
     */
    public function getMaxTimeLimit()
    {
        return $this->maxTimeLimit;
    }

    /**
     * Set adaptive
     *
     * @param boolean $adaptive
     *
     * @return QtiItem
     */
    public function setAdaptive($adaptive)
    {
        $this->adaptive = $adaptive;

        return $this;
    }

    /**
     * Get adaptive
     *
     * @return boolean
     */
    public function getAdaptive()
    {
        return $this->adaptive;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return QtiItem
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set difficulty
     *
     * @param integer $difficulty
     *
     * @return QtiItem
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return integer
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Set toolName
     *
     * @param string $toolName
     *
     * @return QtiItem
     */
    public function setToolName($toolName)
    {
        $this->toolName = $toolName;

        return $this;
    }

    /**
     * Get toolName
     *
     * @return string
     */
    public function getToolName()
    {
        return $this->toolName;
    }

    /**
     * Set toolVersion
     *
     * @param string $toolVersion
     *
     * @return QtiItem
     */
    public function setToolVersion($toolVersion)
    {
        $this->toolVersion = $toolVersion;

        return $this;
    }

    /**
     * Get toolVersion
     *
     * @return string
     */
    public function getToolVersion()
    {
        return $this->toolVersion;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     *
     * @return QtiItem
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return integer
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set parent
     *
     * @param boolean $parent
     *
     * @return QtiItem
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return boolean
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set online
     *
     * @param integer $online
     *
     * @return QtiItem
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return integer
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set effectiveDateFrom
     *
     * @param \DateTime $effectiveDateFrom
     *
     * @return QtiItem
     */
    public function setEffectiveDateFrom($effectiveDateFrom)
    {
        $this->effectiveDateFrom = $effectiveDateFrom;

        return $this;
    }

    /**
     * Get effectiveDateFrom
     *
     * @return \DateTime
     */
    public function getEffectiveDateFrom()
    {
        return $this->effectiveDateFrom;
    }

    /**
     * Set effectiveDateTo
     *
     * @param \DateTime $effectiveDateTo
     *
     * @return QtiItem
     */
    public function setEffectiveDateTo($effectiveDateTo)
    {
        $this->effectiveDateTo = $effectiveDateTo;

        return $this;
    }

    /**
     * Get effectiveDateTo
     *
     * @return \DateTime
     */
    public function getEffectiveDateTo()
    {
        return $this->effectiveDateTo;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItem
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
     * @return QtiItem
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
     * @return QtiItem
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
     * @return QtiItem
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
     * Set status
     *
     * @param \QuizzingPlatform\Entity\QtiStatus $status
     *
     * @return QtiItem
     */
    public function setStatus(\QuizzingPlatform\Entity\QtiStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \QuizzingPlatform\Entity\QtiStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set itemType
     *
     * @param \QuizzingPlatform\Entity\QtiItemType $itemType
     *
     * @return QtiItem
     */
    public function setItemType(\QuizzingPlatform\Entity\QtiItemType $itemType = null)
    {
        $this->itemType = $itemType;

        return $this;
    }

    /**
     * Get itemType
     *
     * @return \QuizzingPlatform\Entity\QtiItemType
     */
    public function getItemType()
    {
        return $this->itemType;
    }
}
