<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/user", name="user_")
 */
class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function login(AuthenticationUtils $authUtils)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home_index');
        }

        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return [
            'error' => $error,
            'last_username' => $lastUsername,
        ];
    }

    /**
     * @Route("/follow/{username}", name="follow")
     */
    public function follow(User $user, Request $request)
    {
        $this->getUser()->follow($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($this->getUser());
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/unfollow/{username}", name="unfollow")
     */
    public function unfollow(User $user, Request $request)
    {
        $this->getUser()->unfollow($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($this->getUser());
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
