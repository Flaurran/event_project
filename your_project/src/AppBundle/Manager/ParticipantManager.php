<?php
namespace AppBundle\Manager;

use AppBundle\Entity\Participant;
use AppBundle\Entity\Project;

class ParticipantManager extends Manager
{
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
        //TODO: Remove all option of participant
        return $this->setStatus($participant, Participant::STATUS_REFUSED);
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
}