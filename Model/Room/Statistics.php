<?php

namespace GorkaLaucirica\HipchatAPIv2Client\Model\Room;

use GorkaLaucirica\HipchatAPIv2Client\Model\ModelBase;

class Statistics extends ModelBase
{
    /**
     * @var \DateTime|null
     */
    protected $lastActive;

    /**
     * @var int
     */
    protected $linksShared;

    /**
     * @var int
     */
    protected $messagesSent;

    /**
     * @return \DateTime|null
     */
    public function getLastActive()
    {
        return $this->lastActive;
    }

    /**
     * @param \DateTime|null $lastActive
     * @return $this
     */
    public function setLastActive($lastActive)
    {
        if (!$lastActive instanceof \DateTime) {
            $lastActive = (null == $lastActive)
                ? "@0" // unix timestamp of 0
                : $lastActive;
            $lastActive = new \DateTime($lastActive);
        }

        $this->lastActive = $lastActive;

        return $this;
    }

    /**
     * @return int
     */
    public function getLinksShared()
    {
        return $this->linksShared;
    }

    /**
     * @param int $linksShared
     * @return $this
     */
    public function setLinksShared($linksShared)
    {
        $this->linksShared = $linksShared;

        return $this;
    }

    /**
     * @return int
     */
    public function getMessagesSent()
    {
        return $this->messagesSent;
    }

    /**
     * @param int $messagesSent
     * @return $this
     */
    public function setMessagesSent($messagesSent)
    {
        $this->messagesSent = $messagesSent;

        return $this;
    }


}