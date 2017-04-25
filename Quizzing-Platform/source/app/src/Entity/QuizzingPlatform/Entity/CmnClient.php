<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmnClient
 *
 * @ORM\Table(name="cmn_client", indexes={@ORM\Index(name="client_id", columns={"client_code"}), @ORM\Index(name="id", columns={"client_id"}), @ORM\Index(name="secret_code", columns={"secret_key"})})
 * @ORM\Entity
 */
class CmnClient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="client_code", type="string", length=255, nullable=false)
     */
    private $clientCode;

    /**
     * @var string
     *
     * @ORM\Column(name="secret_key", type="string", length=255, nullable=false)
     */
    private $secretKey;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=255, nullable=false)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_image", type="string", length=255, nullable=false)
     */
    private $logoImage;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_url", type="string", length=255, nullable=false)
     */
    private $redirectUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="metadata_id", type="integer", nullable=true)
     */
    private $metadataId;

    /**
     * @var string
     *
     * @ORM\Column(name="random_client_metadata_id", type="string", length=10, nullable=false)
     */
    private $randomClientMetadataId;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255, nullable=false)
     */
    private $domain;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime", nullable=false)
     */
    private $modifiedDate;



    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientCode
     *
     * @param string $clientCode
     *
     * @return CmnClient
     */
    public function setClientCode($clientCode)
    {
        $this->clientCode = $clientCode;

        return $this;
    }

    /**
     * Get clientCode
     *
     * @return string
     */
    public function getClientCode()
    {
        return $this->clientCode;
    }

    /**
     * Set secretKey
     *
     * @param string $secretKey
     *
     * @return CmnClient
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Get secretKey
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     *
     * @return CmnClient
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set logoImage
     *
     * @param string $logoImage
     *
     * @return CmnClient
     */
    public function setLogoImage($logoImage)
    {
        $this->logoImage = $logoImage;

        return $this;
    }

    /**
     * Get logoImage
     *
     * @return string
     */
    public function getLogoImage()
    {
        return $this->logoImage;
    }

    /**
     * Set redirectUrl
     *
     * @param string $redirectUrl
     *
     * @return CmnClient
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * Get redirectUrl
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return CmnClient
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
     * Set metadataId
     *
     * @param integer $metadataId
     *
     * @return CmnClient
     */
    public function setMetadataId($metadataId)
    {
        $this->metadataId = $metadataId;

        return $this;
    }

    /**
     * Get metadataId
     *
     * @return integer
     */
    public function getMetadataId()
    {
        return $this->metadataId;
    }

    /**
     * Set randomClientMetadataId
     *
     * @param string $randomClientMetadataId
     *
     * @return CmnClient
     */
    public function setRandomClientMetadataId($randomClientMetadataId)
    {
        $this->randomClientMetadataId = $randomClientMetadataId;

        return $this;
    }

    /**
     * Get randomClientMetadataId
     *
     * @return string
     */
    public function getRandomClientMetadataId()
    {
        return $this->randomClientMetadataId;
    }

    /**
     * Set domain
     *
     * @param string $domain
     *
     * @return CmnClient
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return CmnClient
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
     * Set modifiedDate
     *
     * @param \DateTime $modifiedDate
     *
     * @return CmnClient
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
}
