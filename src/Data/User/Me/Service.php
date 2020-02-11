<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User\Me
 */
class Service extends
    AbstractService
{
    private const REQUEST_GET_CONFIGURATION    = [
        'endpoint' => '/data/users/me',
        'method'   => 'GET',
    ];
    private const REQUEST_UPDATE_CONFIGURATION = [
        'endpoint'      => '/data/users/me',
        'method'        => 'PUT',
        'normalization' => [
            AbstractNormalizer::GROUPS => [
                'update',
            ],
        ],
    ];
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getMe(
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'me'      => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ],
                $schema
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'me' => null === $response['me']
                    ? null
                    : ModelFactory::createUser($response['me']),
            ]
        );
    }
    
    /**
     * updateMe
     *
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function updateMe(
        User $userModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_UPDATE_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $userModel
                ],
                $schema
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
