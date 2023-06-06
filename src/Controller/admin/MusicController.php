<?php

namespace App\Controller\admin;

use App\Entity\Music;
use App\Form\MusicType;
use App\Repository\MusicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MusicController extends AbstractController
{
    public function __construct(
        private readonly MusicRepository $musicRepository,
        private readonly SluggerInterface $slugger
    )
    {
    }

    #[Route('/music', name: 'app_admin_music_index')]
    public function index(Request $request): Response
    {
        $musics = $this->musicRepository->findAll();
        if ($request->get('muz') != null) {
            $muzname = $this->musicRepository->find($request->get('muz'));
        } else {
            $muzname = null;
        }

        return $this->render('admin/music/index.html.twig', [
            'musics' => $musics,
            'muzname' => $muzname
        ]);
    }

    #[Route('/music/add', name: 'app_admin_music_add')]
    public function music_add(Request $request, MusicRepository $repository, SluggerInterface $slugger): Response
    {
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFilename = $this->saveFile($form);
            $music->setFile($newFilename);
            $repository->save($music, true);
            return $this->redirectToRoute('app_admin_music_index');
        }


        return $this->render('admin/music/music_add.html.twig', [
            'MusicAdd' => $form->createView()
        ]);
    }

    #[Route('/music/delete/{id}', name: 'app_admin_music_delete')]
    public function music_delete(Request $request): Response
    {
        $music_request = $request->get('id');
        $music = $this->musicRepository->find($music_request);
        if ('uploads/'.$music->getFile() != null) {
            unlink('uploads/' . $music->getFile());
            $this->musicRepository->remove($music);
            return $this->redirectToRoute('app_admin_music_index');
        }
        throw $this->createNotFoundException();
    }

    #[Route('/music/edit/{id}', name: 'app_admin_music_edit')]
    public function music_edit(MusicRepository $musicRepository , Music $music, Request $request): Response
    {

        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $music->removeFile();
            $fileName = $this->saveFile($form);
            $music->setFile($fileName);
            $musicRepository->update($music);
            return $this->redirectToRoute('app_admin_music_index');
        }

        return $this->render('admin/music/music_edit.html.twig',[
            'MusicAdd' => $form->createView()
        ]);
    }

    public function saveFile(FormInterface $form): string
    {
        /** @var UploadedFile $uploadedFile */

        $uploadedFile = $form->get('file')->getData();
        if ($uploadedFile) {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move('uploads', $newFilename);
            return $newFilename;
        }
        throw new BadRequestException('File not found!');

    }
}
