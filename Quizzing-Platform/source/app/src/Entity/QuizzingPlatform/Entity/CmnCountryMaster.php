<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnCountryMaster
 *
 * @ORM\Table(name="cmn_country_master", indexes={@ORM\Index(name="two_digit_code", columns={"two_digit_code"})})
 * @ORM\Entity
 */
class CmnCountryMaster
{
    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $countryId;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=255, nullable=false)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="three_digit_code", type="string", length=3, nullable=false)
     */
    private $threeDigitCode;

    /**
     * @var string
     *
     * @ORM\Column(name="two_digit_code", type="string", length=2, nullable=false)
     */
    private $twoDigitCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country_currency", type="string", length=20, nullable=false)
     */
    private $countryCurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="region_id", type="integer", nullable=false)
     */
    private $regionId;

    /**
     * @var string
     *
     * @ORM\Column(name="currencysymbol", type="string", length=5, nullable=false)
     */
    private $currencysymbol;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=5, nullable=false)
     */
    private $currencyCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_country", type="integer", nullable=false)
     */
    private $orderCountry;



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
     * Set countryName
     *
     * @param string $countryName
     *
     * @return CmnCountryMaster
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set threeDigitCode
     *
     * @param string $threeDigitCode
     *
     * @return CmnCountryMaster
     */
    public function setThreeDigitCode($threeDigitCode)
    {
        $this->threeDigitCode = $threeDigitCode;

        return $this;
    }

    /**
     * Get threeDigitCode
     *
     * @return string
     */
    public function getThreeDigitCode()
    {
        return $this->threeDigitCode;
    }

    /**
     * Set twoDigitCode
     *
     * @param string $twoDigitCode
     *
     * @return CmnCountryMaster
     */
    public function setTwoDigitCode($twoDigitCode)
    {
        $this->twoDigitCode = $twoDigitCode;

        return $this;
    }

    /**
     * Get twoDigitCode
     *
     * @return string
     */
    public function getTwoDigitCode()
    {
        return $this->twoDigitCode;
    }

    /**
     * Set countryCurrency
     *
     * @param string $countryCurrency
     *
     * @return CmnCountryMaster
     */
    public function setCountryCurrency($countryCurrency)
    {
        $this->countryCurrency = $countryCurrency;

        return $this;
    }

    /**
     * Get countryCurrency
     *
     * @return string
     */
    public function getCountryCurrency()
    {
        return $this->countryCurrency;
    }

    /**
     * Set regionId
     *
     * @param integer $regionId
     *
     * @return CmnCountryMaster
     */
    public function setRegionId($regionId)
    {
        $this->regionId = $regionId;

        return $this;
    }

    /**
     * Get regionId
     *
     * @return integer
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * Set currencysymbol
     *
     * @param string $currencysymbol
     *
     * @return CmnCountryMaster
     */
    public function setCurrencysymbol($currencysymbol)
    {
        $this->currencysymbol = $currencysymbol;

        return $this;
    }

    /**
     * Get currencysymbol
     *
     * @return string
     */
    public function getCurrencysymbol()
    {
        return $this->currencysymbol;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     *
     * @return CmnCountryMaster
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Set orderCountry
     *
     * @param integer $orderCountry
     *
     * @return CmnCountryMaster
     */
    public function setOrderCountry($orderCountry)
    {
        $this->orderCountry = $orderCountry;

        return $this;
    }

    /**
     * Get orderCountry
     *
     * @return integer
     */
    public function getOrderCountry()
    {
        return $this->orderCountry;
    }
}
