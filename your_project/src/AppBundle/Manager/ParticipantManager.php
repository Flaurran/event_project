<?php
namespace AppBundle\Manager;

use AppBundle\Entity\Participant;
use AppBundle\Entity\Project;
use AppBundle\Exception\ParticipantNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ParticipantManager extends Manager
{
    /**
     * @var UserManager
     */
    protected $userManager;

    public function __construct(
        Registry $doctrine,
        TokenStorage $tokenStorage,
        $className = null,
        UserManager $userManager
    ) {
        parent::__construct($doctrine, $tokenStorage, $className);
        $this->userManager = $userManager;
    }

    /**
     * @param object|null   $entity
     * @param bool          $save
     * @param bool          $flush
     * @param array         $args
     *
     * @return Participant
     */
    public function create($entity = null, $save = false, $flush = true, array $args = [])
    {
        if (is_null($entity)) {
            if ($save) {
                throw new \LogicException('Cannot create participant without mail');
            }
            $participant = parent::create();
            $participant->setSlug(parent::random_str(64));
            return $participant;
        }

        if (!$entity instanceof Participant) {
            $classname = get_class($entity);
            throw new \LogicException('Entity must be instance of '
                .Participant::class.". $classname given.");
        }
        if (! isset($args['project'])) {
            throw new \InvalidArgumentException('Missing project argument');
        }

        if (! $args['project'] instanceof Project) {
            throw new \LogicException('Project argument must be instance of '
                .Project::class);
        }

        $project = $args['project'];

        $entity->setProject($project);
        $entity->setSlug(parent::random_str(64));
        $user = $this->userManager->findOneByEmail($entity->getMail());
        if ($user) {
            $entity->setUser($user);
        }

        $this->canBeSave($entity, $save, $flush);

        return $entity;
    }

    /**
     * @param $slug
     *
     * @return Participant|null
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @param Participant $participant
     *
     * @return Participant
     */
    public function accepte(Participant $participant)
    {
        return $this->setStatus($participant, Participant::STATUS_ACCEPTED);
    }

    /**
     * @param Participant $participant
     *
     * @return Participant
     */
    public function decline(Participant $participant)
    {
        //TODO: [v2] Remove all option of participant
        return $this->setStatus($participant, Participant::STATUS_REFUSED);
    }

    /**
     * @param Participant $participant
     *
     * @return Participant
     */
    public function maybe(Participant $participant)
    {
        return $this->setStatus($participant, Participant::STATUS_MAYBE);
    }

    /**
     * @param Participant $participant
     * @param             $status
     * @param bool        $save
     * @param bool        $flush
     *
     * @return Participant
     */
    private function setStatus(Participant $participant, $status, $save = true, $flush = true)
    {
        $participant->setStatus($status);
        $this->canBeSave($participant, $save, $flush);
        return $participant;
    }

    /**
     * @param $id
     *
     * @return Participant
     */
    public function findParticipantById($id)
    {
        $participant = $this->find($id);
        if (! $participant || ! ($participant instanceof Participant) ) {
            throw new ParticipantNotFoundException('Participant with @id=' . $id . ' not found');
        }
        return $participant;
    }

    /**
     * @param $slug
     *
     * @return \AppBundle\Entity\Participant|null
     */
    public function getParticipant($slug)
    {
        $participant = $this->findOneBySlug($slug);
        if (! $participant) {
            throw new ParticipantNotFoundException("Participant with @slug=$slug not found");
        }
        return $participant;
    }
}