<?php
namespace AppBundle\Manager;


class UserManager extends Manager
{
    public function findOneByEmail($email)
    {
        return $this->getRepository()->findOneBy(['email' => $email]);
    }
}