<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class ArticleService
{
    const ROLE_ABLE_TO_DELETE = ['ROLE_ADMIN'];

    private $em;
    private $repository;

    /**
     * __construct
     */
    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository)
    {
        $this->em = $em;
        $this->repository = $articleRepository;
    }

    /**
     * Get all Article and format it 
     * @return array
     */
    public function getList(): array
    {
        return array_map(
            function (Article $article) {
                return [
                    'id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'subtitle' => $article->getSubtitle(),
                    'author' => $article->getUser()->getUsername(),
                    'content' => $article->getContent(),
                    'date' =>($article->getUpdatedDate()) ?  $article->getUpdatedDate()->format('Y-m-d H:i:s') : $article->getCreatedDate()->format('Y-m-d H:i:s'),
                ];
            },
            $this->repository->findAll()
        );
    }

    /**
     * delete one Article
     * @parm id
     * @return void
     */
    public function deleteOneById(int $id, User $user): void
    {
        $article = $this->repository->findOneById($id);
        // check if exist
        if (!$article) {
            throw new \Exception('There are no articles with the following id: ' . $id);
        }
        // check if user have enouth right
        if (!array_intersect($user->getRoles(), self::ROLE_ABLE_TO_DELETE)) {
            throw new \Exception('You do not have enouth right to delete this article the following id: ' . $id);
        }
        $this->em->remove($article);
        $this->em->flush();
    }

    /**
     * get one Article and return it
     * @parm id
     * @return Article
     */
    public function getOneById(int $id): ?Article
    {
        return $this->repository->findOneById($id);
    }

    /**
     * Create one Article and return it
     * @parm  
     * @return array
     */
    public function saveOne(Form $form, User $user): array
    {
        $error = '';
        try {
            $article = $form->getData();
            // user connected is the author
            $article->setUser($user);
            // save it 
            $this->em->persist($article);
            $this->em->flush($article);
        } catch (UniqueConstraintViolationException $ex) {
            // Unique Constraint Violation case
            $article = null;
            $error = 'This title already used. Choose another one';
        } catch (\Exception $ex) {
            // any else Exception
            $article = null;
            $error = 'An Error occured.';
        }

        return  [
            'article' => $article,
            'errorMessage' => $error
        ];
    }
}
