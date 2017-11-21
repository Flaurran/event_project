<?php
namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;
}