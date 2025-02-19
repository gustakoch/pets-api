<?php

declare(strict_types=1);

namespace App\Shared\Test;

use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends BaseKernelTestCase
{
    protected const LOGIN_URL = 'api/v1/auth/login';

    protected Response $response;

    protected mixed $responseContent;

    protected ?string $token = null;

    protected function handleRequest(
        string $path,
        string $method = Request::METHOD_GET,
        ?string $content = null,
    ): void {
        $request = Request::create($path, $method, content: $content);
        $request->headers->set('Content-Type', 'application/json');
        if (null !== $this->token) {
            $request->headers->set('Authorization', 'Bearer '.$this->token);
        }
        $this->response = static::$kernel->handle($request);
        $this->responseContent = json_decode($this->response->getContent(), true);
    }

    protected function iAmLoggedAsTest(): void
    {
        $this->iAmLoggedAs('test@test.com', 'secretPass1');
    }

    protected function iAmLoggedAs(string $email, string $password): void
    {
        $this->handleRequest(
            self::LOGIN_URL,
            Request::METHOD_POST,
            json_encode(
                [
                    'email' => $email,
                    'password' => $password,
                ]
            )
        );
        if (!\array_key_exists('accessToken', $this->responseContent)) {
            throw new \InvalidArgumentException('Invalid token: '.$this->response);
        }
        $this->token = $this->responseContent['accessToken'];
    }

    protected function assertIsSuccess(int $code = Response::HTTP_OK): void
    {
        Assert::assertEquals($code, $this->response->getStatusCode());
    }

    protected function assertIsFailed(int $code = Response::HTTP_UNPROCESSABLE_ENTITY): void
    {
        Assert::assertEquals($code, $this->response->getStatusCode());
    }

    protected function debugResponse(): void
    {
        var_dump($this->response->getContent());
    }
}
