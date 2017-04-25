<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QtiTestLatestVersion
 *
 * @ORM\Table(name="qti_test_latest_version")
 * @ORM\Entity
 */
class QtiTestLatestVersion
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
     * @ORM\Column(name="test_id", type="integer", nullable=false)
     */
    private $testId;

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;



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
     * Set testId
     *
     * @param integer $testId
     *
     * @return QtiTestLatestVersion
     */
    public function setTestId($testId)
    {
        $this->testId = $testId;

        return $this;
    }

    /**
     * Get testId
     *
     * @return integer
     */
    public function getTestId()
    {
        return $this->testId;
    }

    /**
     * Set version
     *
     * @param integer $version
     *
     * @return QtiTestLatestVersion
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }
}
