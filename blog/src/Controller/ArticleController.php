<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\ArticleService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleController extends AbstractController
{
    private $service;

    public function __construct(ArticleService $articleService)
    {
        $this->service = $articleService;
    }
    /**
     * @Route("/articles", name="articles")
     */
    public function index(): Response
    {
        // show all article 
        return $this->render('articles/articles.html.twig', [
            'view' => 'list',
            'deleteRight' => (!!(array_intersect($this->getUser()->getRoles(), $this->service::ROLE_ABLE_TO_DELETE))),
            'articles' => $this->service->getList()
        ]);
    }

    /**
     * @Route("/articles/create")
     */
    public function createAction(Request $request)
    {
        // init my form
        $article = new Article();
        $errorMessage = null;
        $showForm = true;
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('subtitle', TextType::class, ['required' => false])
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'New Article'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $createResponse = $this->service->saveOne($form, $this->getUser());
            if ($createResponse['errorMessage']) {
                $errorMessage = $createResponse['errorMessage'];
            } else {
                $article = $createResponse['article'];
                return $this->redirect('/articles/view/' . $article->getId(), 201);
            }
        }

        return $this->render(
            'articles/articles.html.twig',
            [
                'form' => $form->createView(),
                'view' => 'createOrUpdate',
                'showForm' => $showForm,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/articles/view/{id}")
     */
    public function viewAction($id)
    {
        $errorMessage = null;
        $article = $this->service->getOneById($id);

        // manage if it not exist
        if (!$article) {
            $errorMessage = 'There are no articles with the following id: ' . $id;
        }

        return $this->render(
            'articles/view.html.twig',
            [
                'article' => $article,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/articles/delete/{id}")
     */
    public function deleteAction($id)
    {
        $errorMessage = null;
        try {
            $this->service->deleteOneById($id, $this->getUser());
        } catch (\Exception $ex) {
            //TO DO 
            // Show an alert and do not redirect
            throw new \Exception($ex->getMessage());
        }

        return $this->redirect('/');
    }

    /**
     * @Route("/articles/update/{id}")
     */
    public function updateAction(Request $request, $id)
    {
        // init my form
        $errorMessage = null;
        $showForm = true;
        $article = $this->service->getOneById($id);

        if (!$article) {
            $errorMessage = 'There are no articles with the following id: ' . $id;
            $showForm = false;
        }

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('subtitle', TextType::class, ['required' => false])
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Update Article'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $createResponse = $this->service->saveOne($form, $this->getUser());
            if ($createResponse['errorMessage']) {
                $errorMessage = $createResponse['errorMessage'];
            } else {
                $article = $createResponse['article'];
                return $this->redirect('/articles/view/' . $article->getId(), 201);
            }
        }

        return $this->render(
            'articles/articles.html.twig',
            [
                'view' => 'createOrUpdate',
                'form' => $form->createView(),
                'showForm' => $showForm,
                'errorMessage' => $errorMessage
            ]
        );
    }
}