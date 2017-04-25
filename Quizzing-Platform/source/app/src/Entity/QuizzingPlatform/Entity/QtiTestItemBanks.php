<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiTestItemBanks
 *
 * @ORM\Table(name="qti_test_item_banks", indexes={@ORM\Index(name="fk__qti_test_item_banks__qti_item_bank_idx", columns={"item_bank_id"}), @ORM\Index(name="item_bank_id", columns={"item_bank_id"}), @ORM\Index(name="test_pk_id", columns={"test_pk_id"})})
 * @ORM\Entity
 */
class QtiTestItemBanks
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
     * @var \QuizzingPlatform\Entity\QtiItemBank
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItemBank")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_bank_id", referencedColumnName="item_bank_id")
     * })
     */
    private $itemBank;

    /**
     * @var \QuizzingPlatform\Entity\QtiTest
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiTest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="test_pk_id", referencedColumnName="id")
     * })
     */
    private $testPk;



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
     * Set isDeleted
     *
     * @param integer $isDeleted
     *
     * @return QtiTestItemBanks
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
     * @return QtiTestItemBanks
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
     * @return QtiTestItemBanks
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
     * @return QtiTestItemBanks
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
     * @return QtiTestItemBanks
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
     * Set itemBank
     *
     * @param \QuizzingPlatform\Entity\QtiItemBank $itemBank
     *
     * @return QtiTestItemBanks
     */
    public function setItemBank(\QuizzingPlatform\Entity\QtiItemBank $itemBank = null)
    {
        $this->itemBank = $itemBank;

        return $this;
    }

    /**
     * Get itemBank
     *
     * @return \QuizzingPlatform\Entity\QtiItemBank
     */
    public function getItemBank()
    {
        return $this->itemBank;
    }

    /**
     * Set testPk
     *
     * @param \QuizzingPlatform\Entity\QtiTest $testPk
     *
     * @return QtiTestItemBanks
     */
    public function setTestPk(\QuizzingPlatform\Entity\QtiTest $testPk = null)
    {
        $this->testPk = $testPk;

        return $this;
    }

    /**
     * Get testPk
     *
     * @return \QuizzingPlatform\Entity\QtiTest
     */
    public function getTestPk()
    {
        return $this->testPk;
    }
}
