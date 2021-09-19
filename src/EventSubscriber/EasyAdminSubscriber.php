<?php


namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Event;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;

    private $encoder;

    public function __construct(Security $security, UserPasswordEncoderInterface $encoder)
    {
        $this->security = $security;
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setHiddenFields'],
        ];
    }

    public function setHiddenFields(BeforeEntityPersistedEvent $event)
    {
        $user = $this->security->getUser();
        $entity = $event->getEntityInstance();

        if ($entity instanceof Article && null === $entity->getAuthor()) {
            $entity->setAuthor($user);
            $entity->setIsOnline(false);
        }elseif ($entity instanceof Event && null === $entity->getAuthor()){
            $entity->setAuthor($user);
            $entity->setIsOnline(false);
        }elseif ($entity instanceof User ){
            if(null === $entity->getEnabled()){
                $entity->setEnabled(false);
            }
            if(null === $entity->getRoles()){
                $entity->setRoles('ROLE_USER');
            }

            if(null !== $entity->getPassword()){
                $password = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
                $entity->setPassword($password);
            }

        }else {
            return;
        }

    }
}