<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Client;
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
            'deleteRelationsByCustomer' => [
                'endpoint' => '/access/auth-token/customers/%s/relations',
                'method'   => 'DELETE',
            ],
        ];
    }
    
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
    
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['deleteRelationsByCustomer'],
                [
                    $customerModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
    }
}
