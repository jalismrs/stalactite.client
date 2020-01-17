<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\CertificationType;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\CertificationTypeModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\DataManagement\Client as ParentClient;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/certification/types';
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'            => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'              => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'certificationTypes' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::CERTIFICATION_TYPE
                ]
            ]
        );
    
        $response = $this->requestGet(
            vsprintf(
                '%s%s',
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
                'certificationTypes' => array_map(
                    static function($certificationType) {
                        return ModelFactory::createCertificationType($certificationType);
                    },
                    $response['certificationTypes']
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
    public function get(string $uid, string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'           => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'             => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'certificationType' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CERTIFICATION_TYPE
                ]
            ]
        );
    
        $response = $this->requestGet(
            vsprintf(
                '%s%s/%s',
                [
                    $this->host,
                    self::API_URL_PART,
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
                'certificationType' => $response['certificationType']
                    ? ModelFactory::createCertificationType($response['certificationType'])
                    : null
            ]
        );
    }
    
    /**
     * @param CertificationTypeModel $certificationType
     * @param string                 $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(CertificationTypeModel $certificationType, string $jwt) : Response
    {
        $body = [
            'name' => $certificationType->getName()
        ];
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'           => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'             => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'certificationType' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CERTIFICATION_TYPE
                ]
            ]
        );
    
        $response = $this->requestPost(
            vsprintf(
                '%s%s',
                [
                    $this->host,
                    self::API_URL_PART,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'certificationType' => $response['certificationType']
                    ? ModelFactory::createCertificationType($response['certificationType'])
                    : null
            ]
        );
    }
    
    /**
     * @param CertificationTypeModel $certificationType
     * @param string                 $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(CertificationTypeModel $certificationType, string $jwt) : Response
    {
        $body = ['name' => $certificationType->getName()];
        
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
    
        $response = $this->requestPut(
            vsprintf(
                '%s%s/%s',
                [
                    $this->host,
                    self::API_URL_PART,
                    $certificationType->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
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
    public function delete(string $uid, string $jwt) : Response
    {
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
                '%s%s/%s',
                [
                    $this->host,
                    self::API_URL_PART,
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
