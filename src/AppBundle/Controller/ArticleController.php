<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Shop;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends FOSRestController
{
	/**
     * @Rest\Get("v2/articles/list", name="app_article_list")
     * @Rest\QueryParam(name="order")
     */
    public function listAction($order)
    {
        dump($order);
    }

    /**
     * @Rest\Get(
     *     path = "v2/articles/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
	public function showAction()
    {
		$article = new Article();
		$article->setTitle('Le titre de mon article');
		$article->setContent('Le contenu de mon article');
		
		return $article;
    }
	
    /**
     * @Rest\Post(
     *    path = "v2/articles",
     *    name = "app_article_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function createAction(Article $article)
    {
		$em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

		return $this->view(
			$article, 
			Response::HTTP_CREATED,
			['Location' => $this->generateUrl('app_article_show', ['id' => $article->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }
}