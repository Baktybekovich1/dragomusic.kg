<?php

namespace App\Controller;


use App\Entity\Music;
use App\Form\MusicType;
use App\Repository\MusicRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        $getLastName = $authenticationUtils->getLastUsername();

        return $this->render('index/index.html.twig', [
            'getLastName' => $getLastName
        ]);
    }

    #[Route('/music', name: 'app_index_music')]
    public function music(Request $request, MusicRepository $musicRepository): Response
    {
        $musics = $musicRepository->findAll();
        if ($request->get('muz') != null) {
            $muzname = $musicRepository->find($request->get('muz'));
        }
        else{
            $muzname = null;
        }

        return $this->render('index/music.html.twig',[
            'musics' => $musics,
            'muzname' => $muzname
            ]);
    }

}
