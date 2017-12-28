<?php
namespace AppBundle\Manager;


use AppBundle\Entity\Comment;
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

        $author = $args['author'];

        /** @var Comment $entity */
        $entity
            ->setAuthor($author->getId())
            ->setProject($args['project'])
            ->setAuthorType($entity->getAuthorTypeFromEntity($author))
            ;
        return parent::create($entity, $save, $flush, $args);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function findCommentById($id)
    {
        $comment = $this->find($id);
        if (! $comment) {
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
            switch ($comment->getAuthorType()) {
                case AuthorInterface::AUTHOR_TYPE_USER:
                    $this->calculateCommentForUser($comment);
                    break;
                case AuthorInterface::AUTHOR_TYPE_PARTICIPANT:
                    $this->calculateCommentForParticipant($comment);
                    break;
                default:
                    throw new \LogicException('Author type not found for comment @id=' . $comment->getId());
            }
        }
    }

    //TODO: Factorize these similar function
    /**
     * @param Comment $comment
     */
    public function calculateCommentForUser(Comment $comment)
    {
        $user = $this->userManager->find($comment->getAuthor());
        if (! $user) {
            throw new \LogicException('Comment @id=' . $comment->getId() . ' refer to none exist user @id=' . $comment->getAuthor());
        }
        $comment->setAuthorEntity($user);
    }

    /**
     * @param Comment $comment
     */
    public function calculateCommentForParticipant(Comment $comment)
    {
        $participant = $this->participantManager->find($comment->getAuthor());
        if (! $participant) {
            throw new \LogicException('Comment @id=' . $comment->getId() . ' refer to none exist participant @id=' . $comment->getAuthor());
        }
        $comment->setAuthorEntity($participant);
    }
}