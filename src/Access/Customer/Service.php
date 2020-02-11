<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
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
    private const REQUEST_GET_ACCESS_CLEARANCE_CONFIGURATION = [
        'endpoint' => '/access/customers/%s/access/%s',
        'method'   => 'GET',
        'schema'   => [
            'clearance' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'schema' => Schema::ACCESS_CLEARANCE
            ]
        ],
    ];
    private const REQUEST_GET_RELATIONS_CONFIGURATION        = [
        'endpoint' => '/access/customers/%s/relations',
        'method'   => 'GET',
        'schema'   => [
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
    ];
    
    private $serviceMe;
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
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_RELATIONS_CONFIGURATION,
                [
                    $customerModel->getUid(),
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
                'relations' => array_map(
                    static function(array $relation) use ($customerModel): DomainCustomerRelation {
                        return ModelFactory::createDomainCustomerRelation($relation)
                                           ->setCustomer($customerModel);
                    },
                    $response['relations']
                )
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
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_ACCESS_CLEARANCE_CONFIGURATION,
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
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'clearance' => ModelFactory::createAccessClearance($response['clearance'])
            ]
        );
    }
}
