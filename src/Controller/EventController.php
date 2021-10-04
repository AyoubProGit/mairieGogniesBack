<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EventController extends AbstractController
{

    /**
     * @var EventRepository
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

    public function __construct(EventRepository $repository, EntityManagerInterface $em, KernelInterface $kernel)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->projectDir = $kernel;
    }


    /**
     * @Route("/events/list", name="events.list")
     */
    public function listEvents(): JsonResponse
    {
        $events = $this->em->getRepository(Event::class)->findBy(array('is_online' => true), array('created_at' => 'DESC'));

        $webPath = $this->projectDir->getProjectDir() . '/public';
        $url = $webPath."/uploads/images/featured/";

        $data = array();
        foreach ($events as $key => $event){

            $imageName = $event->getImage()->getName();
            $path = $url.$imageName;
            $content = file_get_contents($path);
            $encode = base64_encode($content);
            $image = "data:image/png;base64,".$encode;
            $data[$key]['id'] = $event->getId();
            $data[$key]['title'] = $event->getTitle();
            $data[$key]['content'] = $event->getDescription();
            $data[$key]['dateEvent'] = $event->getDate();
            $data[$key]['place'] = $event->getPlace();
            $data[$key]['createdAt'] = $event->getCreatedAt();
            $data[$key]['updatedAt'] = $event->getupdatedAt();
            $data[$key]['author'] = $event->getAuthor()->getUsername();
            $data[$key]['image'] = $image;

        }
        return new JsonResponse($data);
    }


}
