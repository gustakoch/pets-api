<?php

declare(strict_types=1);

namespace App\User\Test\Functional;

use App\Shared\Test\ApiTestCase;
use Doctrine\Common\DataFixtures\Loader;
use App\User\Test\DataFixtures\RolesFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

class RegisterControllerTest extends ApiTestCase
{
    protected const REGISTER = 'api/v1/register/user';

    public function testRegisterNewUser(): void
    {
        $this->markTestSkipped('Not sending real emails for testing');

        $this->loadFixtures();
        $this->handleRequest(
            self::REGISTER,
            method: Request::METHOD_POST,
            content: json_encode([
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'johndoe@email.com',
                'password' => 'secretPass1',
            ])
        );
        $this->assertIsSuccess(Response::HTTP_CREATED);
        $this->assertEquals('John', $this->responseContent['firstname']);
        $this->assertEquals('Doe', $this->responseContent['lastname']);
        $this->assertEquals('johndoe@email.com', $this->responseContent['email']);
        $this->assertEquals('active', $this->responseContent['status']);
        $this->assertEquals('guest', $this->responseContent['role']['name']);
    }

    private function loadFixtures(): void
    {
        $loader = new Loader();
        $loader->addFixture(new RolesFixture());

        $executor = new ORMExecutor($this->entityManager, new ORMPurger());
        $executor->execute($loader->getFixtures());
    }
}
