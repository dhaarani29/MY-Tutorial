<?php

namespace QuizzingPlatform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogQtiItemProcess
 *
 * @ORM\Table(name="log_qti_item_process", indexes={@ORM\Index(name="item_bank_upload_id", columns={"item_bank_upload_id"}), @ORM\Index(name="item_bank_id", columns={"item_bank_id"})})
 * @ORM\Entity
 */
class LogQtiItemProcess
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
     * @ORM\Column(name="log", type="text", nullable=false)
     */
    private $log;

    /**
     * @var \QuizzingPlatform\Entity\QtiItemBankUpload
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItemBankUpload")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_bank_upload_id", referencedColumnName="id")
     * })
     */
    private $itemBankUpload;

    /**
     * @var \QuizzingPlatform\Entity\QtiItemBank
     *
     * @ORM\ManyToOne(targetEntity="QuizzingPlatform\Entity\QtiItemBank")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_bank_id", referencedColumnName="item_bank_id")
     * })
     */
    private $itemBank;



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
     * Set log
     *
     * @param string $log
     *
     * @return LogQtiItemProcess
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set itemBankUpload
     *
     * @param \QuizzingPlatform\Entity\QtiItemBankUpload $itemBankUpload
     *
     * @return LogQtiItemProcess
     */
    public function setItemBankUpload(\QuizzingPlatform\Entity\QtiItemBankUpload $itemBankUpload = null)
    {
        $this->itemBankUpload = $itemBankUpload;

        return $this;
    }

    /**
     * Get itemBankUpload
     *
     * @return \QuizzingPlatform\Entity\QtiItemBankUpload
     */
    public function getItemBankUpload()
    {
        return $this->itemBankUpload;
    }

    /**
     * Set itemBank
     *
     * @param \QuizzingPlatform\Entity\QtiItemBank $itemBank
     *
     * @return LogQtiItemProcess
     */
    public function setItemBank(\QuizzingPlatform\Entity\QtiItemBank $itemBank = null)
    {
        $this->itemBank = $itemBank;

        return $this;
    }

    /**
     * Get itemBank
     *
     * @return \QuizzingPlatform\Entity\QtiItemBank
     */
    public function getItemBank()
    {
        return $this->itemBank;
    }
}
