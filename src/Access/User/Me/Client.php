<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\User\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Access\User\Me
 */
class Client extends
    AbstractClient
{
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getRelations(string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relations' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => [
                        'uid' => [
                            'type' => JsonRule::STRING_TYPE
                        ],
                        'domain' => [
                            'type' => JsonRule::OBJECT_TYPE,
                            'schema' => DataSchema::DOMAIN
                        ]
                    ]
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/access/users/me/relations',
                [
                    $this->getHost(),
                ],
            ),
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
                'relations' => array_map(
                    static function (array $relation): DomainUserRelation {
                        return ModelFactory::createDomainUserRelation($relation);
                    },
                    $response['relations']
                )
            ]
        );
    }

    /**
     * getAccessClearance
     *
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAccessClearance(
        Domain $domainModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'clearance' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'schema' => Schema::ACCESS_CLEARANCE
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/access/users/me/access/%s',
                [
                    $this->getHost(),
                    $domainModel->getUid(),
                ],
            ),
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
                'clearance' => ModelFactory::createAccessClearance($response['clearance'])
            ]
        );
    }
}
