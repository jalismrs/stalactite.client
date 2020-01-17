<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Customer\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\AccessManagement\Customer\Client as ParentClient;
use Jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\AccessManagement\Schema;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\CustomerModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/me';
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getRelations(
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'   => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relations' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => [
                        'uid'    => [
                            'type' => JsonRule::STRING_TYPE
                        ],
                        'domain' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'schema' => DataManagementSchema::DOMAIN
                        ]
                    ]
                ]
            ]
        );
    
        $response = $this->requestGet(
            vsprintf(
                '%s%s/relations',
                [
                    $this->host,
                    self::API_URL_PART,
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
                    static function($relation) {
                        return ModelFactory::createDomainCustomerRelation($relation);
                    },
                    $response['relations']
                )
            ]
        );
    }
    
    /**
     * @param DomainModel $domain
     * @param string      $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAccessClearance(DomainModel $domain, string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'   => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'clearance' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'schema' => Schema::ACCESS_CLEARANCE
                ]
            ]
        );
    
        $response = $this->requestGet(
            vsprintf(
                '%s%s/access/%s',
                [
                    $this->host,
                    self::API_URL_PART,
                    $domain->getUid(),
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
