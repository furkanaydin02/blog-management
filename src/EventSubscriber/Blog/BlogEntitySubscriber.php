<?php

namespace App\EventSubscriber\Blog;

use App\Entity\Blog;
use App\Entity\BlogAuthor;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BlogEntitySubscriber implements EventSubscriberInterface
{
    public function onBeforePersist(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Blog || $entity instanceof BlogAuthor) {
            $entity->setCreatedAt(new \DateTime());
        }
    }

    public function onBeforeUpdate(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Blog || $entity instanceof BlogAuthor) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['onBeforePersist'],
            BeforeEntityUpdatedEvent::class => ['onBeforeUpdate'],
        ];
    }
}
