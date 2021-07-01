<?php

namespace App\Tests\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ArticleServiceTest extends TestCase
{
    public function __construct()
    {
        $em = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->articleRepository = $this
            ->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->articleService = new ArticleService(
            $em,
            $this->articleRepository
        );
    }

    public function testGetList()
    {
        $article_1 = new Article();
        $article_1
            ->setTitle('azerty')
            ->setSubtitle('sub azerty')
            ->setContent('abcdefghijklmnopqrstuvwxyz');

        $article_2 = new Article();
        $article_2
            ->setTitle('azerty')
            ->setSubtitle('sub azerty')
            ->setContent('abcdefghijklmnopqrstuvwxyz');
        
        $this->articleRepository->method('findAll')->willReturn([$article_1, $article_2]);
        $list = $this->articleService->getList();
        $this->assertCount(2,$list);
    }
}
