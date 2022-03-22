<?php

namespace App\Tests;

use App\Entity\Utilisateur;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    public function testValidUser(): void
    {
        $user = new Utilisateur();
        $user->setEmail('test@test.com')
            ->setPassword('azerty')
            ->setDateInscription(new DateTime())
            ->setDateDerniereConnection(new DateTime())
            ->setDateNaissance(new DateTime())
            ->setPrenom('John')
            ->setNom('Doe')
            ->setRoles(["ROLE_USER"]);
        $kernel = self::bootKernel();
        $this->validator = $kernel->getContainer()->get('validator');
        $errors = $this->validator->validate($user);

        $messages = [];
        foreach($errors as $error){
            $messages[] = $error->getPropertyPath() .' => '. $error->getMessage() ;
        }
        $this->assertCount(0, $errors, implode(', ', $messages));
        //$routerService = static::getContainer()->get('router');
        //$myCustomService = static::getContainer()->get(CustomService::class);
    }
}
