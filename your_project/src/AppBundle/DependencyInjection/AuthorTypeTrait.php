<?php
namespace AppBundle\DependencyInjection;

trait AuthorTypeTrait
{
    public function getAuthorTypeFromEntity($entity)
    {
        if (! is_object($entity)) {
            throw new \LogicException('Cannot get author type from none object');
        }
        $authorType = null;
        switch (get_class($entity)) {
            case \AppBundle\Entity\User::class:
                $authorType = \AppBundle\EntityInterface\AuthorInterface::AUTHOR_TYPE_USER;
                break;
            case \AppBundle\Entity\Participant::class:
                $authorType = \AppBundle\EntityInterface\AuthorInterface::AUTHOR_TYPE_PARTICIPANT;
                break;
            default:
                break;
        }

        if (is_null($authorType)) {
            throw new \LogicException('Author type not found for @class=' . get_class($entity));
        }

        return $authorType;
    }
}