<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\RequestConfiguration;
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
    private $serviceMe;
    
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
            'getAccessClearance' => (new RequestConfiguration(
                '/access/customers/%s/access/%s'
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
                '/access/customers/%s/relations'
            ))
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
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * me
     *
     * @return Me\Service
     */
    public function me() : Me\Service
    {
        if (null === $this->serviceMe) {
            $this->serviceMe = new  Me\Service($this->getClient());
        }
        
        return $this->serviceMe;
    }
    
    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * getRelations
     *
     * @param Customer $customerModel
     * @param string   $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getRelations(
        Customer $customerModel,
        string $jwt
    ) : Response {
        $requestConfiguration = $this->requestConfigurations['getRelations'];
        assert($requestConfiguration instanceof RequestConfiguration);
        
        $requestConfiguration->setResponse(
            static function(array $response) use ($customerModel) : array {
                return [
                    'relations' => array_map(
                        static function(array $relation) use ($customerModel): DomainCustomerRelation {
                            return ModelFactory::createDomainCustomerRelation($relation)
                                               ->setCustomer($customerModel);
                        },
                        $response['relations']
                    ),
                ];
            }
        );
        
        return $this
            ->getClient()
            ->request(
                $requestConfiguration,
                [
                    $customerModel->getUid(),
                ],
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
     * @param Customer $customerModel
     * @param Domain   $domainModel
     * @param string   $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAccessClearance(
        Customer $customerModel,
        Domain $domainModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAccessClearance'],
                [
                    $customerModel->getUid(),
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
