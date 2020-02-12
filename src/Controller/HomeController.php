<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\Recherche;
use App\Form\RechercheType;
use App\Repository\BiensRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomeController extends AbstractController
{
    

    /**
     * @Route("/", name="home")
     */
    public function index(BiensRepository $biensRepository, PaginatorInterface $paginator, Request $request)
    {
        $recherche = new Recherche();
        $form = $this->createForm(RechercheType::class, $recherche);
        $form->handleRequest($request);


        return $this->render('home/index.html.twig', [
            'biens' => $paginator->paginate(
                $biensRepository->findAllVisibleQuery($recherche),
                $request->query->getInt('page', 1), 4),
            'form'  => $form->createView()

        ]);
    }
    
    /**
    * @Route("/{id}", name="bien_detail", methods={"GET"}, requirements={"id"="\d+"})
    * @ParamConverter("id", options={"id": "id"})
    */
    public function detail(Biens $bien): Response
    {
        return $this->render('home/detail.html.twig', [
            'bien' => $bien,
        ]);
    }
}
