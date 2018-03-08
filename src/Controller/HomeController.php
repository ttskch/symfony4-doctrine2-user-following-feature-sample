<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/", name="home_")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return compact('users');
    }
}
