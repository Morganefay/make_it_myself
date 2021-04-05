<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordEncoderSubscriber implements EventSubscriberInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function onFormPostSubmit($event)
    {
        $form = $event->getForm();
        $user = $event->getData();
        $plainTextPassword = $form->get('plaintextPassword')->getData();

        if ($plainTextPassword) {
            $hashedPassword = $this->encoder->encodePassword($user, $plainTextPassword);
            $user->setPassword($hashedPassword);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'form.post_submit' => 'onFormPostSubmit',
        ];
    }
}
