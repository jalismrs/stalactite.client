<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Response;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Access\AuthToken\Domain
 */
class Client extends
    AbstractClient
{
    /**
     * deleteRelationsByDomain
     *
     * @param Domain $domainModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteRelationsByDomain(
        Domain $domainModel,
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this->getUserAgent()
        );

        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this->delete(
            vsprintf(
                '%s/access/auth-token/domains/%s/relations',
                [
                    $this->getHost(),
                    $domainModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => (string)$jwt
                ]
            ],
            $schema
        );

        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
