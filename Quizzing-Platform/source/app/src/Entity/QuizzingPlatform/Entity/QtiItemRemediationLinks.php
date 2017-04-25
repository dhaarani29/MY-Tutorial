<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiItemRemediationLinks
 *
 * @ORM\Table(name="qti_item_remediation_links", indexes={@ORM\Index(name="fk_question_remediation_links__remediation_link_type_idx", columns={"remediation_link_type_id"}), @ORM\Index(name="fk__question_remediation_links__question_idx", columns={"item_pk_id"})})
 * @ORM\Entity
 */
class QtiItemRemediationLinks
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
     * @var string
     *
     * @ORM\Column(name="link_text1", type="string", length=255, nullable=false)
     */
    private $linkText1;

    /**
     * @var string
     *
     * @ORM\Column(name="link_text2", type="string", length=255, nullable=true)
     */
    private $linkText2;

    /**
     * @var string
     *
     * @ORM\Column(name="link_text3", type="string", length=255, nullable=true)
     */
    private $linkText3;

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
     * @var \QuizzingPlatform\Entity\QtiItem
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_pk_id", referencedColumnName="id")
     * })
     */
    private $itemPk;

    /**
     * @var \QuizzingPlatform\Entity\QtiRemediationLinkType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiRemediationLinkType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="remediation_link_type_id", referencedColumnName="remediation_link_type_id")
     * })
     */
    private $remediationLinkType;



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
     * Set linkText1
     *
     * @param string $linkText1
     *
     * @return QtiItemRemediationLinks
     */
    public function setLinkText1($linkText1)
    {
        $this->linkText1 = $linkText1;

        return $this;
    }

    /**
     * Get linkText1
     *
     * @return string
     */
    public function getLinkText1()
    {
        return $this->linkText1;
    }

    /**
     * Set linkText2
     *
     * @param string $linkText2
     *
     * @return QtiItemRemediationLinks
     */
    public function setLinkText2($linkText2)
    {
        $this->linkText2 = $linkText2;

        return $this;
    }

    /**
     * Get linkText2
     *
     * @return string
     */
    public function getLinkText2()
    {
        return $this->linkText2;
    }

    /**
     * Set linkText3
     *
     * @param string $linkText3
     *
     * @return QtiItemRemediationLinks
     */
    public function setLinkText3($linkText3)
    {
        $this->linkText3 = $linkText3;

        return $this;
    }

    /**
     * Get linkText3
     *
     * @return string
     */
    public function getLinkText3()
    {
        return $this->linkText3;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return QtiItemRemediationLinks
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
     * @return QtiItemRemediationLinks
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
     * @return QtiItemRemediationLinks
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
     * @return QtiItemRemediationLinks
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
     * Set itemPk
     *
     * @param \QuizzingPlatform\Entity\QtiItem $itemPk
     *
     * @return QtiItemRemediationLinks
     */
    public function setItemPk(\QuizzingPlatform\Entity\QtiItem $itemPk = null)
    {
        $this->itemPk = $itemPk;

        return $this;
    }

    /**
     * Get itemPk
     *
     * @return \QuizzingPlatform\Entity\QtiItem
     */
    public function getItemPk()
    {
        return $this->itemPk;
    }

    /**
     * Set remediationLinkType
     *
     * @param \QuizzingPlatform\Entity\QtiRemediationLinkType $remediationLinkType
     *
     * @return QtiItemRemediationLinks
     */
    public function setRemediationLinkType(\QuizzingPlatform\Entity\QtiRemediationLinkType $remediationLinkType = null)
    {
        $this->remediationLinkType = $remediationLinkType;

        return $this;
    }

    /**
     * Get remediationLinkType
     *
     * @return \QuizzingPlatform\Entity\QtiRemediationLinkType
     */
    public function getRemediationLinkType()
    {
        return $this->remediationLinkType;
    }
}
