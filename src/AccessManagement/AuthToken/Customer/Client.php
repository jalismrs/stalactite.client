<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Customer;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Client as ParentClient;
use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel;
use Jalismrs\Stalactite\Client\Response;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\AuthToken\CustomerModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/customers';
    
    /**
     * deleteRelationsByCustomer
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel $customerModel
     * @param string                                                         $apiAuthToken
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function deleteRelationsByCustomer(
        CustomerModel $customerModel,
        string $apiAuthToken
    ) : Response {
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
                    $customerModel->getUid(),
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
