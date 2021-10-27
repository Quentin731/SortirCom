<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Trip;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    private $entityManager;
    private $errorMessage;

    /**
     * ProductController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = new Search();

        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $search->getUserSearch() != '') {
            $trips = $this->entityManager->getRepository(Trip::class)->findWithSearch($search);
        } else {
            $trips = $this->entityManager->getRepository(Trip::class)->findAll();
        }

        return $this->render('home/index.html.twig', [
            'listeSorties' => $trips,
            'errorMessage'=>$this->errorMessage,
            'tripFilter' => $form->createView()
        ]);
    }
}
