<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\FilterType;
use App\Form\RegistrationFormType;
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
        $errorMessage ="";
    }
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $sorties = $this->entityManager->getRepository(Trip::class)->findAll();
        $this->errorMessage=$request->query->get('error');


        return $this->render('home/index.html.twig', [
            'listeSorties' => $sorties,
            'errorMessage'=>$this->errorMessage
        ]);
    }
}
