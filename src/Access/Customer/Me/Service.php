<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Customer\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Customer
 */
class Service extends
    AbstractService
{
    /**
     * Service constructor.
     *
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'getAccessClearance' => [
                'endpoint'   => '/access/customers/me/access/%s',
                'method'     => 'GET',
                'validation' => [
                    'clearance' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'schema' => Schema::ACCESS_CLEARANCE
                    ]
                ],
            ],
            'getRelations'       => [
                'endpoint'   => '/access/customers/me/relations',
                'method'     => 'GET',
                'validation' => [
                    'relations' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => [
                            'uid'    => [
                                'type' => JsonRule::STRING_TYPE
                            ],
                            'domain' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'schema' => DataSchema::DOMAIN
                            ]
                        ]
                    ]
                ],
            ],
        ];
    }
    
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
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getRelations'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'relations' => array_map(
                    static function(array $relation) : DomainCustomerRelation {
                        return ModelFactory::createDomainCustomerRelation($relation);
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
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAccessClearance'],
                [
                    $domainModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
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
