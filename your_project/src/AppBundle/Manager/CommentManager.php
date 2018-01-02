<?php
namespace AppBundle\Manager;


use AppBundle\Entity\Comment;
use AppBundle\EntityInterface\AuthorEntityInterface;
use AppBundle\EntityInterface\AuthorInterface;
use AppBundle\Exception\CommentNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CommentManager extends Manager
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var ParticipantManager
     */
    protected $participantManager;

    public function __construct(
        Registry $doctrine,
        TokenStorage $tokenStorage,
        $className,
        UserManager $userManager,
        ParticipantManager $participantManager
    ) {
        parent::__construct($doctrine, $tokenStorage, $className);
        $this->userManager = $userManager;
        $this->participantManager = $participantManager;
    }

    /**
     * @param null  $entity
     * @param bool  $save
     * @param bool  $flush
     * @param array $args
     *
     * @return mixed
     */
    public function create($entity = null, $save = false, $flush = true, array $args = [])
    {
        if (is_null($entity)) {
            throw new \LogicException('Cannot create an empty Comment');
        }
        $this->validateCreateEntity($entity);
        if (! isset($args['author']) || ! isset($args['project'])) {
            throw new \InvalidArgumentException("User and project must be defined to create Comment entity");
        }

        /** @var AuthorEntityInterface $author */
        $author = $args['author'];

        /** @var Comment $entity */
        $entity
            ->setAuthor($author->getId())
            ->setProject($args['project'])
            ->setAuthorType($entity->getAuthorTypeFromEntity($author))
            ->setAuthorName($author->getAuthorName())
            ;
        return parent::create($entity, $save, $flush, $args);
    }

    /**
     * @param $id
     *
     * @return Comment
     */
    public function findCommentById($id)
    {
        $comment = $this->find($id);
        if (! $comment || ! ($comment instanceof Comment) ) {
            throw new CommentNotFoundException('Comment with @id=' . $id . ' not found');
        }
        return $comment;
    }

    /**
     * @param $author
     * @param $project
     *
     * @return array
     */
    public function createArgs($author, $project)
    {
        return [
            'author' => $author,
            'project' => $project,
        ];
    }

    /**
     * @param Comment[] $comments
     */
    public function calculate($comments)
    {
        /** @var Comment $comment */
        foreach ($comments as $comment) {
            $manager = null;
            switch ($comment->getAuthorType()) {
                case AuthorInterface::AUTHOR_TYPE_USER:
                    $manager = $this->userManager;
                    break;
                case AuthorInterface::AUTHOR_TYPE_PARTICIPANT:
                    $manager = $this->participantManager;
                    break;
                default:
                    throw new \LogicException('Author type not found for comment @id=' . $comment->getId());
            }
            $this->setAuthorEntity($manager, $comment);
        }
    }

    /**
     * @param Manager $manager
     * @param Comment $comment
     */
    public function setAuthorEntity(Manager $manager, Comment $comment)
    {
        $author = $manager->find($comment->getAuthor());
        if (! $author || ! ($author instanceof AuthorEntityInterface)) {
            throw new \LogicException('Comment @id=' . $comment->getId() . ' refer to none exist user @id=' . $comment->getAuthor());
        }
        $comment->setAuthorEntity($author);
    }
}