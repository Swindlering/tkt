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
       return $this->ListAction('name', 'ASC');
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

    /**
     * @Route("/societies/show/{type}/{order}/{page}")
     */
    public function ListAction($type = null, $order = null, $page = 1)
    {
        // TO DO change that to search any thing not only name
        $search = null;

        $errorMessage = null;
        $societies = $this->service->getList($search, $type, $order, $page, 15);
        
        if (!$societies) {
            $errorMessage = 'Not found';
        }

        // show all society
        return $this->render('societies/societies.html.twig', [
            'view' => 'list',
            'order' => ( $order === 'DESC') ? 'ASC' : 'DESC',
            'orderActual' => $order,
            'errorMessage' => $errorMessage,
            'societies' => $societies['data'],
            'page' => $societies['page'],
            'nbPages' => $societies['nbPages']
        ]);
    }
}