<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrgUserProfile
 *
 * @ORM\Table(name="org_user_profile", indexes={@ORM\Index(name="fk__user_profile__user_type_idx", columns={"user_type_id"}), @ORM\Index(name="client_pk_id", columns={"client_id"})})
 * @ORM\Entity
 */
class OrgUserProfile
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="client_user_id", type="string", length=50, nullable=true)
     */
    private $clientUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=50, nullable=true)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", length=50, nullable=true)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="string", length=50, nullable=true)
     */
    private $emailAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
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
     * @ORM\Column(name="user_belongs_to", type="smallint", nullable=true)
     */
    private $userBelongsTo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effective_date", type="datetime", nullable=true)
     */
    private $effectiveDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", nullable=true)
     */
    private $modifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime", nullable=false)
     */
    private $modifiedDate;

    /**
     * @var \QuizzingPlatform\Entity\OrgUserType
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\OrgUserType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_type_id", referencedColumnName="user_type_id")
     * })
     */
    private $userType;

    /**
     * @var \QuizzingPlatform\Entity\CmnClient
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\CmnClient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="client_id")
     * })
     */
    private $client;



    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set clientUserId
     *
     * @param string $clientUserId
     *
     * @return OrgUserProfile
     */
    public function setClientUserId($clientUserId)
    {
        $this->clientUserId = $clientUserId;

        return $this;
    }

    /**
     * Get clientUserId
     *
     * @return string
     */
    public function getClientUserId()
    {
        return $this->clientUserId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return OrgUserProfile
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return OrgUserProfile
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return OrgUserProfile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return OrgUserProfile
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return OrgUserProfile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return OrgUserProfile
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return OrgUserProfile
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return OrgUserProfile
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
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
     * @return OrgUserProfile
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
     * @return OrgUserProfile
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
     * Set userBelongsTo
     *
     * @param integer $userBelongsTo
     *
     * @return OrgUserProfile
     */
    public function setUserBelongsTo($userBelongsTo)
    {
        $this->userBelongsTo = $userBelongsTo;

        return $this;
    }

    /**
     * Get userBelongsTo
     *
     * @return integer
     */
    public function getUserBelongsTo()
    {
        return $this->userBelongsTo;
    }

    /**
     * Set effectiveDate
     *
     * @param \DateTime $effectiveDate
     *
     * @return OrgUserProfile
     */
    public function setEffectiveDate($effectiveDate)
    {
        $this->effectiveDate = $effectiveDate;

        return $this;
    }

    /**
     * Get effectiveDate
     *
     * @return \DateTime
     */
    public function getEffectiveDate()
    {
        return $this->effectiveDate;
    }

    /**
     * Set modifiedBy
     *
     * @param integer $modifiedBy
     *
     * @return OrgUserProfile
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
     * @return OrgUserProfile
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
     * Set userType
     *
     * @param \QuizzingPlatform\Entity\OrgUserType $userType
     *
     * @return OrgUserProfile
     */
    public function setUserType(\QuizzingPlatform\Entity\OrgUserType $userType = null)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return \QuizzingPlatform\Entity\OrgUserType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set client
     *
     * @param \QuizzingPlatform\Entity\CmnClient $client
     *
     * @return OrgUserProfile
     */
    public function setClient(\QuizzingPlatform\Entity\CmnClient $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \QuizzingPlatform\Entity\CmnClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
