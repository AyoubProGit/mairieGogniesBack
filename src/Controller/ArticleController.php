<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @var ArticleRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var KernelInterface
     */
    private $projectDir;

    public function __construct(ArticleRepository $repository, EntityManagerInterface $em, KernelInterface $kernel)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->projectDir = $kernel;
    }

    /**
     * @Route("/articles/list", name="article.list")
     */
    public function listArticles(): JsonResponse
    {
        $articles = $this->em->getRepository(Article::class)->findBy(array('is_online' => true), array('created_at' => 'DESC'));

        $webPath = $this->projectDir->getProjectDir() . '/public';
        $url = $webPath."/uploads/images/featured/";

        $data = array();
        foreach ($articles as $key => $article){

            $imageName = $article->getImage()->getName();
            $path = $url.$imageName;
            $content = file_get_contents($path);
            $encode = base64_encode($content);
            $image = "data:image/png;base64,".$encode;

            $data[$key]['id'] = $article->getId();
            $data[$key]['title'] = $article->getTitle();
            $data[$key]['content'] = $article->getContent();
            $data[$key]['tag'] = $article->getTag();
            $data[$key]['createdAt'] = $article->getCreatedAt();
            $data[$key]['updatedAt'] = $article->getupdatedAt();
            $data[$key]['author'] = $article->getAuthor()->getUsername();
            $data[$key]['image'] = $image;

        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/articles/travaux", name="article.travaux")
     */
    public function listTravauxArticles(): JsonResponse
    {
        $travaux = $this->repository->filteredByTag("travaux");
        $webPath = $this->projectDir->getProjectDir() . '/public';
        $url = $webPath."/uploads/images/featured/";

        $data = array();
        foreach ($travaux as $key => $travail){

            $imageName = $travail->getImage()->getName();
            $path = $url.$imageName;
            $content = file_get_contents($path);
            $encode = base64_encode($content);
            $image = "data:image/png;base64,".$encode;
            $data[$key]['id'] = $travail->getId();
            $data[$key]['title'] = $travail->getTitle();
            $data[$key]['content'] = $travail->getContent();
            $data[$key]['tag'] = $travail->getTag();
            $data[$key]['createdAt'] = $travail->getCreatedAt();
            $data[$key]['updatedAt'] = $travail->getupdatedAt();
            $data[$key]['author'] = $travail->getAuthor()->getUsername();
            $data[$key]['image'] = $image;
        }
        return new JsonResponse($data);
    }

}
