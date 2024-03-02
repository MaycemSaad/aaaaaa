<?php

namespace App\Controller;

use App\Entity\Projets;
use App\Form\ProjetsType;
use App\Repository\ProjetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projets')]
class ProjetsController extends AbstractController
{
    #[Route('/', name: 'app_projets_index', methods: ['GET'])]
    public function index(ProjetsRepository $projetsRepository): Response
    {
        return $this->render('projets/index.html.twig', [
            'projets' => $projetsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_projets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projets();
        if(!$projet->getDateDeCreation()){
            $projet->setDateDeCreation(new \DateTime());}
    
            $form = $this->createForm(ProjetsType::class, $projet);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $photoFile = $form->get('photoURL')->getData();
    
                // Vérifiez si un fichier a été téléchargé
                if ($photoFile) {
                    // Générez un nom de fichier unique
                    $newFilename = uniqid().'.'.$photoFile->guessExtension();
    
                    // Déplacez le fichier vers le répertoire où sont stockées les photos
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
    
                    // Mettez à jour l'URL de l'image dans l'entité Project
                    $projet->setPhotoUrl($newFilename);
                }
                $entityManager->persist($projet);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
            }
    
        return $this->render('projets/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_projets_show', methods: ['GET'])]
    public function show(Projets $projet): Response
    {
        return $this->render('projets/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_projets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projets $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
        }
                $projet = new Projets();
        if(!$projet->getDateDeCreation()){
            $projet->setDateDeCreation(new \DateTime());}
    
            $form = $this->createForm(ProjetsType::class, $projet);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $photoFile = $form->get('photoURL')->getData();
    
                // Vérifiez si un fichier a été téléchargé
                if ($photoFile) {
                    // Générez un nom de fichier unique
                    $newFilename = uniqid().'.'.$photoFile->guessExtension();
    
                    // Déplacez le fichier vers le répertoire où sont stockées les photos
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
    
                    // Mettez à jour l'URL de l'image dans l'entité Project
                    $projet->setPhotoUrl($newFilename);
                }
                $entityManager->persist($projet);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
            }


        return $this->render('projets/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_projets_delete', methods: ['POST'])]
    public function delete(Request $request, Projets $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
    }
}
