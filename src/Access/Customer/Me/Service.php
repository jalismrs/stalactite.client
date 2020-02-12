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
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
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
     *
     * @throws RequestConfigurationException
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'getAccessClearance' => (new RequestConfiguration(
                '/access/customers/me/access/%s'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'clearance' => ModelFactory::createAccessClearance($response['clearance']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'clearance' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'schema' => Schema::ACCESS_CLEARANCE
                        ]
                    ]
                ),
            'getRelations'       => (new RequestConfiguration(
                '/access/customers/me/relations'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'relations' => array_map(
                                static function(array $relation) : DomainCustomerRelation {
                                    return ModelFactory::createDomainCustomerRelation($relation);
                                },
                                $response['relations']
                            ),
                        ];
                    }
                )
                ->setValidation(
                    [
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
                    ]
                ),
        ];
    }
    
    /**
     * getRelations
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getRelations(
        string $jwt
    ) : Response {
        return $this
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
     * @throws SerializerException
     */
    public function getAccessClearance(
        Domain $domainModel,
        string $jwt
    ) : Response {
        return $this
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
    }
}
