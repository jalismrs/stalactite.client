<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\Customer
 */
class Service extends
    AbstractService
{
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
    public function me(): Me\Service
    {
        if (null === $this->serviceMe) {
            $this->serviceMe = new Me\Service($this->getClient());
        }

        return $this->serviceMe;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * getAllCustomers
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllCustomers(
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'customers' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->get(
            '/data/customers',
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
                'customers' => array_map(
                    static function ($customer) {
                        return ModelFactory::createCustomer($customer);
                    },
                    $response['customers']
                )
            ]
        );
    }

    /**
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getCustomer(
        string $uid,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'customer' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->get(
            vsprintf(
                '/data/customers/%s',
                [
                    $uid,
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
                'customer' => null === $response['customer']
                    ? null
                    : ModelFactory::createCustomer($response['customer']),
            ]
        );
    }

    /**
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'customer' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->get(
            '/data/customers',
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'query' => [
                    'email' => $email,
                    'googleId' => $googleId
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'customer' => null === $response['customer']
                    ? null
                    : ModelFactory::createCustomer($response['customer']),
            ]
        );
    }

    /**
     * createCustomer
     *
     * @param Customer $customerModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function createCustomer(
        Customer $customerModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'customer' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->post(
            '/data/customers',
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => Serializer::getInstance()->normalize(
                    $customerModel,
                    [
                        AbstractNormalizer::GROUPS => [
                            'create',
                        ],
                    ]
                )
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'customer' => null === $response['customer']
                    ? null
                    : ModelFactory::createCustomer($response['customer']),
            ]
        );
    }

    /**
     * updateCustomer
     *
     * @param Customer $customerModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function updateCustomer(
        Customer $customerModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->put(
            vsprintf(
                '/data/customers/%s',
                [
                    $customerModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => Serializer::getInstance()->normalize(
                    $customerModel,
                    [
                        AbstractNormalizer::GROUPS => [
                            'update',
                        ],
                    ]
                )
            ],
            $schema
        );

        return (new Response(
            $response['success'],
            $response['error']
        ));
    }

    /**
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteCustomer(
        string $uid,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->delete(
            vsprintf(
                '/data/customers/%s',
                [
                    $uid,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
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
