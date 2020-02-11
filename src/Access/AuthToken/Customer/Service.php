<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\Customer
 */
class Service extends
    AbstractService
{
    private const REQUEST_DELETE_RELATIONS_BY_CUSTOMER_CONFIGURATION = [
        'endpoint' => '/access/auth-token/customers/%s/relations',
        'method'   => 'DELETE',
    ];
    
    /**
     * deleteRelationsByCustomer
     *
     * @param Customer $customerModel
     * @param string   $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteRelationsByCustomer(
        Customer $customerModel,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_DELETE_RELATIONS_BY_CUSTOMER_CONFIGURATION,
                [
                    $customerModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
