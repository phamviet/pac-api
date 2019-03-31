<?php

namespace App\EventSubscriber;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class SecuritySubscriber implements EventSubscriberInterface
{
    private $registry;

    /**
     * SecuritySubscriber constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function onSecurityAuthenticationSuccess(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface) {
            $roles = $user->getRoles();
            if (in_array('ROLE_LDAP', $roles)) {
                $this->persistLdapUser($user);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onSecurityAuthenticationSuccess',
        ];
    }

    /**
     * @param UserInterface $user
     */
    public function persistLdapUser(UserInterface $user)
    {
        $manager = $this->registry->getManager();
        $repository = $manager->getRepository(User::class);
        $current = $repository->findOneBy(['username' => $user->getUsername()]);

        if (!$current) {
            $current = new User();
            $current
                ->setUsername($user->getUsername())
                ->setName($user->getUsername())
                ->setRoles(['ROLE_USER'])
                ->setLoggedAt(new \DateTime())
                ->setCreatedAt(new \DateTime())
                ->setModifiedAt(new \DateTime());
        } else {
            $current->setLastLoggedAt($current->getLoggedAt());
            $current->setLoggedAt(new \DateTime());
        }

        $manager->persist($current);
        $manager->flush();
    }
}
