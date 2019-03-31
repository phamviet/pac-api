<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTSubscriber implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    private $registry;

    /**
     * JWTSubscriber constructor.
     * @param RequestStack $requestStack
     * @param ManagerRegistry $registry
     */
    public function __construct(RequestStack $requestStack, ManagerRegistry $registry)
    {
        $this->requestStack = $requestStack;
        $this->registry = $registry;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onJWTCreated',
        ];
    }

    /**
     * Inject client ip to token data
     *
     * @param JWTCreatedEvent $event
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest() ?: Request::createFromGlobals();
        $user = $event->getUser();

        $manager = $this->registry->getManager();
        $repository = $manager->getRepository(User::class);
        $current = $repository->findOneBy(['username' => $user->getUsername()]);

        $data = $event->getData();

        if (isset($data['roles'])) {
            unset($data['roles']);
        }

        if (isset($data['tokenId']) && empty($data['tokenId'])) {
            // todo Generate long live token for access control
            $data['ipAddress'] = $request->getClientIp();
        } else {
            $data['uid'] = $current->getId();
        }

        $event->setData($data);
    }
}
