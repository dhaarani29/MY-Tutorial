<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrgUserProfileExtension
 *
 * @ORM\Table(name="org_user_profile_extension", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class OrgUserProfileExtension
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_extn_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userExtnId;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=255, nullable=true)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="address3", type="string", length=255, nullable=true)
     */
    private $address3;

    /**
     * @var string
     *
     * @ORM\Column(name="address4", type="string", length=255, nullable=true)
     */
    private $address4;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=true)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="country", type="integer", nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=50, nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone1", type="string", length=20, nullable=true)
     */
    private $phone1;

    /**
     * @var string
     *
     * @ORM\Column(name="phone2", type="string", length=20, nullable=true)
     */
    private $phone2;

    /**
     * @var string
     *
     * @ORM\Column(name="reset_password_token", type="text", length=65535, nullable=true)
     */
    private $resetPasswordToken;

    /**
     * @var integer
     *
     * @ORM\Column(name="reset_password_identifier", type="smallint", nullable=true)
     */
    private $resetPasswordIdentifier = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reset_password_expiry", type="datetime", nullable=true)
     */
    private $resetPasswordExpiry;

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
     * Get userExtnId
     *
     * @return integer
     */
    public function getUserExtnId()
    {
        return $this->userExtnId;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return OrgUserProfileExtension
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return OrgUserProfileExtension
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set address3
     *
     * @param string $address3
     *
     * @return OrgUserProfileExtension
     */
    public function setAddress3($address3)
    {
        $this->address3 = $address3;

        return $this;
    }

    /**
     * Get address3
     *
     * @return string
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * Set address4
     *
     * @param string $address4
     *
     * @return OrgUserProfileExtension
     */
    public function setAddress4($address4)
    {
        $this->address4 = $address4;

        return $this;
    }

    /**
     * Get address4
     *
     * @return string
     */
    public function getAddress4()
    {
        return $this->address4;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return OrgUserProfileExtension
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return OrgUserProfileExtension
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param integer $country
     *
     * @return OrgUserProfileExtension
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return integer
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return OrgUserProfileExtension
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set phone1
     *
     * @param string $phone1
     *
     * @return OrgUserProfileExtension
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * Get phone1
     *
     * @return string
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return OrgUserProfileExtension
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Set resetPasswordToken
     *
     * @param string $resetPasswordToken
     *
     * @return OrgUserProfileExtension
     */
    public function setResetPasswordToken($resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    /**
     * Get resetPasswordToken
     *
     * @return string
     */
    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }

    /**
     * Set resetPasswordIdentifier
     *
     * @param integer $resetPasswordIdentifier
     *
     * @return OrgUserProfileExtension
     */
    public function setResetPasswordIdentifier($resetPasswordIdentifier)
    {
        $this->resetPasswordIdentifier = $resetPasswordIdentifier;

        return $this;
    }

    /**
     * Get resetPasswordIdentifier
     *
     * @return integer
     */
    public function getResetPasswordIdentifier()
    {
        return $this->resetPasswordIdentifier;
    }

    /**
     * Set resetPasswordExpiry
     *
     * @param \DateTime $resetPasswordExpiry
     *
     * @return OrgUserProfileExtension
     */
    public function setResetPasswordExpiry($resetPasswordExpiry)
    {
        $this->resetPasswordExpiry = $resetPasswordExpiry;

        return $this;
    }

    /**
     * Get resetPasswordExpiry
     *
     * @return \DateTime
     */
    public function getResetPasswordExpiry()
    {
        return $this->resetPasswordExpiry;
    }

    /**
     * Set user
     *
     * @param \QuizzingPlatform\Entity\OrgUserProfile $user
     *
     * @return OrgUserProfileExtension
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
}
