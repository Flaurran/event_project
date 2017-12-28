<?php

namespace AppBundle\Entity;

use AppBundle\DependencyInjection\AuthorTypeTrait;
use AppBundle\EntityInterface\AuthorEntityInterface;
use AppBundle\EntityInterface\AuthorInterface;

/**
 * Comment
 */
class Comment implements AuthorInterface
{
    use AuthorTypeTrait;
    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $id;

    /**
     */
    private $author;

    /**
     * @var mixed
     */
    private $authorType;

    /**
     * @var \AppBundle\Entity\Project
     */
    private $project;

    /**
     * Transient
     * @var AuthorEntityInterface
     */
    private $authorEntity;

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

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
     * Set author
     *
     * @param integer $author
     *
     * @return Comment
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Comment
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
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

    /* LifeCycleEvent */

    /**
     *
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getAuthorType()
    {
        return $this->authorType;
    }

    /**
     * @param $authorType
     *
     * @return $this
     */
    public function setAuthorType($authorType)
    {
        $this->authorType = $authorType;

        return $this;
    }

    /**
     * @return AuthorEntityInterface
     */
    public function getAuthorEntity()
    {
        return $this->authorEntity;
    }

    /**
     * @param AuthorEntityInterface $authorEntity
     *
     * @return AuthorEntityInterface
     */
    public function setAuthorEntity(AuthorEntityInterface $authorEntity)
    {
        $this->authorEntity = $authorEntity;

        return $this->authorEntity;
    }

    /**
     * @return bool
     */
    public function authorIsParticipant()
    {
        return $this->authorType === AuthorInterface::AUTHOR_TYPE_PARTICIPANT;
    }

    /**
     * @return bool
     */
    public function authorIsUser()
    {
        return $this->authorType === AuthorInterface::AUTHOR_TYPE_USER;
    }
}

