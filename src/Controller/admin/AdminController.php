<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $getLastName = $authenticationUtils->getLastUsername();
        return $this->render('admin/index.html.twig', [
            'lastName' => $getLastName
        ]);
    }



}
