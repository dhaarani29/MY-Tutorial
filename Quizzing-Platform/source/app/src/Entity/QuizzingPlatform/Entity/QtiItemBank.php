<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemBank
 *
 * @ORM\Table(name="qti_item_bank", indexes={@ORM\Index(name="fk__question_bank__status_idx", columns={"status_id"})})
 * @ORM\Entity
 */
class QtiItemBank
{
    /**
     * @var integer
     *
     * @ORM\Column(name="item_bank_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $itemBankId;

    /**
     * @var string
     *
     * @ORM\Column(name="item_bank_name", type="string", length=50, nullable=false)
     */
    private $itemBankName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effective_date_from", type="datetime", nullable=false)
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
     * @ORM\Column(name="is_deleted", type="smallint", nullable=false)
     */
    private $isDeleted;

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
     * Get itemBankId
     *
     * @return integer
     */
    public function getItemBankId()
    {
        return $this->itemBankId;
    }

    /**
     * Set itemBankName
     *
     * @param string $itemBankName
     *
     * @return QtiItemBank
     */
    public function setItemBankName($itemBankName)
    {
        $this->itemBankName = $itemBankName;

        return $this;
    }

    /**
     * Get itemBankName
     *
     * @return string
     */
    public function getItemBankName()
    {
        return $this->itemBankName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return QtiItemBank
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set effectiveDateFrom
     *
     * @param \DateTime $effectiveDateFrom
     *
     * @return QtiItemBank
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
     * @return QtiItemBank
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
     * Set isDeleted
     *
     * @param integer $isDeleted
     *
     * @return QtiItemBank
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
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemBank
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
     * @return QtiItemBank
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
     * @return QtiItemBank
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
     * @return QtiItemBank
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
     * @return QtiItemBank
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
}
