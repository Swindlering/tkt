<?php

namespace App\Controller;

use App\Entity\Society;
use App\Service\SocietyService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SocietyController extends AbstractController
{
    private $service;

    public function __construct(SocietyService $societyService)
    {
        $this->service = $societyService;
    }
    /**
     * @Route("/societies", name="societies")
     */
    public function index(): Response
    {
        // show all society 
        return $this->render('societies/societies.html.twig', [
            'view' => 'list',
            'societies' => $this->service->getList()
        ]);
    }

    /**
     * @Route("/societies/view/{id}")
     */
    public function viewAction($id)
    {
        $errorMessage = null;
        $society = $this->service->getOneById($id);

        // manage if it not exist
        if (!$society) {
            $errorMessage = 'There are no societies with the following id: ' . $id;
        }

        return $this->render(
            'societies/view.html.twig',
            [
                'society' => $society,
                'errorMessage' => $errorMessage
            ]
        );
    }



}