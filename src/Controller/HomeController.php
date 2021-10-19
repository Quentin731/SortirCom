<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\FilterType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator


class HomeController extends AbstractController
{

    private $entityManager;

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
    public function index(Request $request,PaginatorInterface $paginator): Response
    {

        $sorties = $this->entityManager->getRepository(Sortie::class)->findAll();
        $paginateSorties= $paginator->paginate(
            $sorties,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/index.html.twig', [
            'listeSorties' => $paginateSorties
        ]);
    }
}
