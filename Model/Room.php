<?php

namespace GorkaLaucirica\HipchatAPIv2Client\Model;

/**
 * Class Room
 *
 * @package GorkaLaucirica\HipchatAPIv2Client\Model
 */
class Room
{
    const PRIVACY_PRIVATE = 'private';
    const PRIVACY_PUBLIC  = 'public';


    /**
     * @var int
     */
    protected $id;

    protected $xmppJid;

    /**
     * @var Room\Statistics
     */
    protected $statistics;

    /**
     * @var string
     */
    protected $name;


    protected $links;

    /**
     * @var string
     */
    protected $created;

    /**
     * @var bool
     */
    protected $archived;

    /**
     * @var string
     */
    protected $privacy;


    protected $guestAccessible;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var array of User
     */
    protected $participants;

    /**
     * @var User
     */
    protected $owner;

    /**
     * @var string
     */
    protected $guestAccessUrl;

    /**
     * Builds a room object from server response if json given, otherwise creates an empty object
     *
     * @param array $json json_decoded response in json given by the server
     *
     * @return self
     */
    public function __construct($json = null)
    {
        if ($json) {
            $this->parseJson($json);
        } else {
            $this->guestAccessible = false;
            $this->privacy         = 'public';
        }
    }

    /**
     * Parses response given by the API and maps the fields to Room object
     *
     * @param array $json json_decoded response in json given by the server
     *
     * @return void
     */
    public function parseJson($json)
    {
        $this->id    = $json['id'];
        $this->name  = $json['name'];
        $this->links = $json['links'];

        if (isset($json['xmpp_jid'])) {
            $this->xmppJid = $json['xmpp_jid'];
        }
        if (isset($json['created'])) {
            $this->created = new \DateTime($json['created']);
        }
        if (isset($json['is_archived'])) {
            $this->archived = $json['is_archived'];
        }
        if (isset($json['privacy'])) {
            $this->privacy = $json['privacy'];
        }
        if (isset($json['is_guest_accessible'])) {
            $this->guestAccessible = $json['is_guest_accessible'];
        }
        if (isset($json['topic'])) {
            $this->topic = $json['topic'];
        }
        if (isset($json['participants'])) {
            $this->participants = array();
            foreach ($json['participants'] as $participant) {
                $this->participants[] = new User($participant);
            }
        }
        if (isset($json['owner'])) {
            $this->owner = new User($json['owner']);
        }
        if (isset($json['guest_access_url'])) {
            $this->guestAccessUrl = $json['guest_access_url'];
        }
    }

    /**
     * Serializes Room object
     *
     * @return array
     */
    public function toJson()
    {
        $json = array();

        $json['name']    = $this->getName();
        $json['privacy'] = $this->getPrivacy();
        //Parameters for PUT call (Room already exists)
        if ($this->getId()) {
            $json['is_archived']         = $this->isArchived();
            $json['is_guest_accessible'] = $this->isGuestAccessible();
            $json['topic']               = $this->getTopic();
            $json['owner']               = array('id' => $this->getOwner()->getId());
        } else { //Paramters for POST call
            $json['guest_access'] = $this->isGuestAccessible();
            if ($this->getOwner()) {
                $json['owner_user_id'] = $this->getOwner()->getId();
            }
        }

        return $json;
    }

    /**
     * Sets id
     *
     * @param integer $id The id to be set
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets XMPP/Jabber ID of the room
     *
     * @param string $xmppJid XMPP/Jabber ID of the room
     *
     * @return self
     */
    public function setXmppJid($xmppJid)
    {
        $this->xmppJid = $xmppJid;

        return $this;
    }

    /**
     * Returns XMPP/Jabber ID of the room
     *
     * @return string
     */
    public function getXmppJid()
    {
        return $this->xmppJid;
    }

    /**
     * Sets name of the room
     *
     * @param mixed $name Name of the room.
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns Name of the room
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets URLs to retrieve room information
     *
     * @param array $links URLs to retrieve room information
     *
     * @return self
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * Returns URLs to retrieve room information
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Sets time the room was created in UTC
     *
     * @param \Datetime $created Time the room was created in UTC
     *
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Returns time the room was created in UTC
     *
     * @return \Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets whether or not this room is archived
     *
     * @param boolean $archived Whether or not this room is archived
     *
     * @return self
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Returns if is archived or not
     *
     * @return mixed
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * Sets Privacy setting
     *
     * @param string $privacy Privacy setting. Valid values: public | private
     *
     * @return self
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * Returns privacy setting
     *
     * @return string public | private
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->getPrivacy() == self::PRIVACY_PUBLIC;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return !$this->isPublic();
    }

    /**
     * Sets whether or not guests can access this room
     *
     * @param boolean $guestAccessible Whether or not guests can access this room
     *
     * @return self
     */
    public function setGuestAccessible($guestAccessible)
    {
        $this->guestAccessible = $guestAccessible;

        return $this;
    }

    /**
     * Returns whether or not guests can access this room
     *
     * @return boolean
     */
    public function isGuestAccessible()
    {
        return $this->guestAccessible;
    }

    /**
     * Sets current topic
     *
     * @param string $topic Current topic
     *
     * @return self
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Returns current topic
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Sets list of current room participants
     *
     * @param array $participants List of current room participants
     *
     * @return self
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * Returns list of current room participants
     *
     * @return array of User
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Sets the room owner
     *
     * @param User $owner The room owner
     *
     * @return self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Returns the room owner
     *
     * @return User
     */
    public function getOwner()
    {
        if (!$this->owner instanceof User) {
            $this->owner = new User();
        }

        return $this->owner;
    }

    /**
     * Sets URL for guest access
     *
     * @param string $guestAccessUrl URL for guest access
     *
     * @return self
     */
    public function setGuestAccessUrl($guestAccessUrl)
    {
        $this->guestAccessUrl = $guestAccessUrl;

        return $this;
    }

    /**
     * Returns URL for guest access, if enabled
     *
     * @return string | null
     */
    public function getGuestAccessUrl()
    {
        return $this->guestAccessUrl;
    }

    /**
     * @return Room\Statistics
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param Room\Statistics $statistics
     * @return $this
     */
    public function setStatistics(Room\Statistics $statistics)
    {
        $this->statistics = $statistics;

        return $this;
    }


}
