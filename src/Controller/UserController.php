<?php
declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}
