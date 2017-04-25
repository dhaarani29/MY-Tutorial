<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrgGroupRoleAssociation
 *
 * @ORM\Table(name="org_group_role_association", indexes={@ORM\Index(name="group_id", columns={"group_id"}), @ORM\Index(name="role_id", columns={"role_id"})})
 * @ORM\Entity
 */
class OrgGroupRoleAssociation
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
     * @var \QuizzingPlatform\Entity\OrgGroup
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\OrgGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
     * })
     */
    private $group;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set group
     *
     * @param \QuizzingPlatform\Entity\OrgGroup $group
     *
     * @return OrgGroupRoleAssociation
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

    /**
     * Set role
     *
     * @param \QuizzingPlatform\Entity\SecRole $role
     *
     * @return OrgGroupRoleAssociation
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
}
