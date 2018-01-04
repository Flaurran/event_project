<?php
namespace AppBundle\Entity;


use AppBundle\EntityInterface\AuthorEntityInterface;

class Participant implements AuthorEntityInterface
{
    const STATUS_INVITE = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REFUSED = 2;
    const STATUS_MAYBE = 3;

    static $statusLabel = [
        self::STATUS_INVITE => 'Invité (non répondu)',
        self::STATUS_ACCEPTED => 'Participe',
        self::STATUS_REFUSED => 'Ne participe pas',
        self::STATUS_MAYBE => 'Peut-être'
    ];

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var \AppBundle\Entity\Project
     */
    private $project;

    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;



    /**
     * Participant constructor.
     */
    public function __construct()
    {
        $this->status = self::STATUS_INVITE;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Participant
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Participant
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Participant
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Participant
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Participant
     */
    public function setProject(\AppBundle\Entity\Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusLabel()
    {
        return self::$statusLabel[$this->getStatus()];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getUser() ? $this->getUser()->getAuthorName() : sprintf('%s %s', $this->getFirstname(), $this->getLastname());
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->getFullName();
    }

    /**
     * @return bool
     */
    public function isInvite()
    {
        return $this->status === self::STATUS_INVITE;
    }

    /**
     * @return bool
     */
    public function isRefused()
    {
        return $this->status === self::STATUS_REFUSED;
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    /**
     * @return bool
     */
    public function isMaybe()
    {
        return $this->status === self::STATUS_MAYBE;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Participant
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
