<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnStateMaster
 *
 * @ORM\Table(name="cmn_state_master")
 * @ORM\Entity
 */
class CmnStateMaster
{
    /**
     * @var integer
     *
     * @ORM\Column(name="state_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $stateId;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     */
    private $countryId;

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=10, nullable=false)
     */
    private $stateCode;

    /**
     * @var string
     *
     * @ORM\Column(name="state_name", type="string", length=255, nullable=false)
     */
    private $stateName;



    /**
     * Get stateId
     *
     * @return integer
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * Set countryId
     *
     * @param integer $countryId
     *
     * @return CmnStateMaster
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return CmnStateMaster
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * Get stateCode
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }

    /**
     * Set stateName
     *
     * @param string $stateName
     *
     * @return CmnStateMaster
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;

        return $this;
    }

    /**
     * Get stateName
     *
     * @return string
     */
    public function getStateName()
    {
        return $this->stateName;
    }
}
