<?php

namespace App\Controller;


use App\Entity\Biens;
use App\Entity\Option;
use App\Form\BiensType;
use App\Repository\BiensRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
 * @Route("/biens")
 */
class BiensController extends AbstractController
{
    /**
     * @Route("/", name="biens_index", methods={"GET"})
     */
    public function index(BiensRepository $biensRepository): Response
    {   
        return $this->render('biens/index.html.twig', [
            'current_menu' => 'biens',
            'biens' => $biensRepository->findLatest('App:Biens'),
        ]);
        
    }

    /**
     * @Route("/new", name="biens_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bien = new Biens();
        $form = $this->createForm(BiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bien);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien créer le bien');

            return $this->redirectToRoute('biens_index');
        }

        return $this->render('biens/new.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="biens_show", methods={"GET"})
     */
    public function show(Biens $bien): Response
    {
        return $this->render('biens/show.html.twig', [
            'biens' => $bien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="biens_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Biens $bien): Response
    {
        
        $form = $this->createForm(BiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                // $safeFilename = transliterator_transliterate("Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()", $originalFilename);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $bien->setFile($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Vous avez bien modifier le bien');

            return $this->redirectToRoute('biens_index');
        
        }

        return $this->render('biens/edit.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("/{id}", name="biens_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Biens $bien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bien);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien supprimé le bien');
        }

        return $this->redirectToRoute('biens_index');
    }
}
