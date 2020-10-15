<?php

namespace Jalismrs\Stalactite\Client\Authentication\Token;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;
use Throwable;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Authentication\Token
 */
class Service extends
    AbstractService
{
    /**
     * @param Token $token
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function validate(Token $token) : Response
    {
        $endpoint = new Endpoint(
            '/auth/tokens',
            'HEAD'
        );
        
        return $this->getClient()
                    ->request(
                        $endpoint,
                        [
                            'jwt' => (string)$token,
                        ]
                    );
    }
    
    /**
     * @param ClientApp $clientApp
     * @param string    $userGoogleJwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function login(
        ClientApp $clientApp,
        string $userGoogleJwt
    ) : Response {
        $endpoint = new Endpoint(
            '/auth/tokens',
            'POST'
        );
        $endpoint
            ->setResponseValidationSchema(new JsonSchema(['token' => ['type' => JsonRule::STRING_TYPE]]))
            ->setResponseFormatter(
                static function(array $response) : array {
                    try {
                        $token = (new Parser())->parse($response['token']);
                    } catch (Throwable $t) {
                        throw new AuthenticationServiceException(
                            'Invalid token',
                            AuthenticationServiceException::INVALID_TOKEN
                        );
                    }
                    
                    return ['token' => $token];
                }
            );
        
        return $this
            ->getClient()
            ->request(
                $endpoint,
                [
                    'json' => [
                        'app' => $clientApp->getName(),
                        'userGoogleJwt' => $userGoogleJwt,
                    ],
                ]
            );
    }
}
