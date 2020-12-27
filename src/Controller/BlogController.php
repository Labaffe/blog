<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
		$donnees = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy([],['date_publication' => 'desc']);
		
		$articles = $paginator->paginate(
			$donnees,
			$request->query->getInt('page',1),
			4
		);
		
        return $this->render('blog/index.html.twig', [
			'articles' => $articles
        ]);
    }
	
	/**
     * @Route("/helloworld", name="helloworld")
     */
    public function helloworld(): Response
    {	
        return $this->render('blog/helloworld.html.twig');
    }
	/**
     * @Route("/post/{alias}", name="post")
     */
    public function post($alias): Response
    {
		$article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneByAlias($alias);
		
        return $this->render('blog/post.html.twig', [
            'article' => $article,
        ]);
    }
	
	/**
	* @Route("/api/articles",name="article")
	*/
	public function apiArticle(EntityManagerInterface $em) : JsonResponse
	{
		$articles = $em->getRepository(Article::class)->findAll();
		$serializedArticles = [];
		foreach($articles as $article) {
			$serializedArticles[] = [
				'id' => $article->getId(),
				'title' => $article->getTitre(),
				'content' => $article->getDescription(),
			];
		}
		return new JsonResponse(['data' => $serializedArticles, 'items' => count($serializedArticles)]);
	}
}
