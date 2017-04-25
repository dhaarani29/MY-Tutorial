<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrgGroupUsers
 *
 * @ORM\Table(name="org_group_users", indexes={@ORM\Index(name="fk__group_users_group_idx", columns={"group_id"}), @ORM\Index(name="fk__group_users_user_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class OrgGroupUsers
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
     * @var \QuizzingPlatform\Entity\OrgUserProfile
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\OrgUserProfile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @var \QuizzingPlatform\Entity\OrgGroup
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\OrgGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
     * })
     */
    private $group;



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
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return OrgGroupUsers
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
     * @return OrgGroupUsers
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
     * @return OrgGroupUsers
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
     * @return OrgGroupUsers
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
     * Set user
     *
     * @param \QuizzingPlatform\Entity\OrgUserProfile $user
     *
     * @return OrgGroupUsers
     */
    public function setUser(\QuizzingPlatform\Entity\OrgUserProfile $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \QuizzingPlatform\Entity\OrgUserProfile
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param \QuizzingPlatform\Entity\OrgGroup $group
     *
     * @return OrgGroupUsers
     */
    public function setGroup(\QuizzingPlatform\Entity\OrgGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \QuizzingPlatform\Entity\OrgGroup
     */
    public function getGroup()
    {
        return $this->group;
    }
}
