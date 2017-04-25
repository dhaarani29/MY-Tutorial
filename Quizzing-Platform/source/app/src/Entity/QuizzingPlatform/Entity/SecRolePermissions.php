<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SecRolePermissions
 *
 * @ORM\Table(name="sec_role_permissions", indexes={@ORM\Index(name="fk__role_permissions_role_idx", columns={"role_id"}), @ORM\Index(name="fk__role_permissions__permission_idx", columns={"permission_id"})})
 * @ORM\Entity
 */
class SecRolePermissions
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
     * @var \QuizzingPlatform\Entity\SecRole
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\SecRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     * })
     */
    private $role;

    /**
     * @var \QuizzingPlatform\Entity\SecPermission
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\SecPermission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="permission_id", referencedColumnName="permission_id")
     * })
     */
    private $permission;



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
     * @return SecRolePermissions
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
     * @return SecRolePermissions
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
     * @return SecRolePermissions
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
     * @return SecRolePermissions
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
     * Set role
     *
     * @param \QuizzingPlatform\Entity\SecRole $role
     *
     * @return SecRolePermissions
     */
    public function setRole(\QuizzingPlatform\Entity\SecRole $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \QuizzingPlatform\Entity\SecRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set permission
     *
     * @param \QuizzingPlatform\Entity\SecPermission $permission
     *
     * @return SecRolePermissions
     */
    public function setPermission(\QuizzingPlatform\Entity\SecPermission $permission = null)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return \QuizzingPlatform\Entity\SecPermission
     */
    public function getPermission()
    {
        return $this->permission;
    }
}
