<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\Customer
 */
class Service extends
    AbstractService
{
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
     * @throws SerializerException
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
                (new Request(
                    '/access/auth-token/customers/%s/relations'
                ))
                    ->setMethod('DELETE')
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => (string)$jwt
                            ]
                        ]
                    )
                    ->setUriDatas(
                        [
                            $customerModel->getUid(),
                        ]
                    )
            );
    }
}
