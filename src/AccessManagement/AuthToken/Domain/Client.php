<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Client as ParentClient;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\AuthToken\DomainModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/domains';
    
    /**
     * @param DomainModel $domain
     * @param string      $apiAuthToken
     *
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function deleteRelationsByDomain(DomainModel $domain, string $apiAuthToken) : Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this->userAgent
        );
        
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
    
        $response = $this->requestDelete(
            vsprintf(
                '%s%s/%s/relations',
                [
                    $this->host,
                    self::API_URL_PART,
                    $domain->getUid(),
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
