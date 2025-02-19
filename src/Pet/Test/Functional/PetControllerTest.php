<?php

declare(strict_types=1);

namespace App\Pet\Test\Functional;

use App\Shared\Test\ApiTestCase;
use Doctrine\Common\DataFixtures\Loader;
use App\Pet\Test\DataFixtures\PetsFixture;
use App\User\Test\DataFixtures\RolesFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User\Test\DataFixtures\UserTestFixture;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

class PetControllerTest extends ApiTestCase
{
    protected const PETS = 'api/v1/pets';
    protected const SHOW = 'api/v1/pets/%s';
    protected const UPDATE = 'api/v1/pets/%s/update';
    protected const DELETE = 'api/v1/pets/%s/delete';

    public function testCreatePet(): void
    {
        $this->loadFixtures();
        $this->iAmLoggedAsTest();

        $this->handleRequest(
            self::PETS,
            method: Request::METHOD_POST,
            content: json_encode([
                'name' => 'Rex',
                'specie' => 'dog',
                'birthDate' => '2020-01-01',
                'color' => 'Brown',
                'description' => 'Very friendly',
                'veterinarianIds' => [],
            ])
        );
        $this->assertIsSuccess(Response::HTTP_CREATED);
    }

    public function testListPets(): void
    {
        $this->loadFixtures();
        $this->iAmLoggedAsTest();

        $this->handleRequest(self::PETS);
        $this->assertIsSuccess();
        $this->assertCount(10, $this->responseContent);
    }

    public function testShowPet(): void
    {
        $this->loadFixtures();
        $this->iAmLoggedAsTest();

        $this->handleRequest(self::PETS);
        $this->assertIsSuccess();
        $this->handleRequest(\sprintf(self::SHOW, $this->responseContent[0]['id']));
        $this->assertIsSuccess();
        $this->assertEquals('Pet 1', $this->responseContent['name']);
    }

    public function testUpdatePet(): void
    {
        $this->loadFixtures();
        $this->iAmLoggedAsTest();

        $this->handleRequest(self::PETS);
        $this->assertIsSuccess();
        $this->handleRequest(
            \sprintf(self::UPDATE, $this->responseContent[0]['id']),
            method: Request::METHOD_PUT,
            content: json_encode([
                'name' => 'Joey',
                'specie' => 'dog',
                'birthDate' => '2020-01-01',
                'color' => 'Black and White',
                'veterinarianIds' => [],
            ])
        );
        $this->assertIsSuccess();
        $this->assertEquals('Joey', $this->responseContent['name']);
        $this->assertEquals('dog', $this->responseContent['specie']);
        $this->assertEquals('Black and White', $this->responseContent['color']);
    }

    public function testDeletePet(): void
    {
        $this->loadFixtures();
        $this->iAmLoggedAsTest();

        $this->handleRequest(
            self::PETS,
            method: Request::METHOD_POST,
            content: json_encode([
                'name' => 'Joey',
                'specie' => 'dog',
                'birthDate' => '2020-01-01',
                'color' => 'Black and White',
                'description' => 'Very friendly',
                'veterinarianIds' => [],
            ])
        );
        $this->assertIsSuccess(Response::HTTP_CREATED);
        $this->handleRequest(
            \sprintf(self::DELETE, $this->responseContent['id']),
            method: Request::METHOD_DELETE
        );
        $this->assertIsSuccess(204);
    }

    private function loadFixtures(): void
    {
        $loader = new Loader();
        $loader->addFixture(new RolesFixture());
        $loader->addFixture(new UserTestFixture());
        $loader->addFixture(new PetsFixture());

        $executor = new ORMExecutor($this->entityManager, new ORMPurger());
        $executor->execute($loader->getFixtures());
    }
}
