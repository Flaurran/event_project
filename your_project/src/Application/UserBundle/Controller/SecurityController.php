<?php

namespace UserBundle\Controller;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends \FOS\UserBundle\Controller\SecurityController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addRoleAdminAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->addRole('ROLE_ADMIN');
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Admin role is adding to ' . $user->getUsername());
        return $this->redirectToRoute('homepage');
    }
}
