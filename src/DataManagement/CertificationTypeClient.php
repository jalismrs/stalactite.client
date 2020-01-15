<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Response;

/**
 * CertificationTypeClient
 *
 * @package Jalismrs\Stalactite\Client\DataManagement
 */
class CertificationTypeClient extends
    AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/certification/types';
    
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
    
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        $certificationTypes = [];
        foreach ($r['certificationTypes'] as $certificationType) {
            $certificationTypes[] = ModelFactory::createCertificationType($certificationType);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'certificationTypes' => $certificationTypes
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
    
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'certificationType' => $r['certificationType']
                    ? ModelFactory::createCertificationType($r['certificationType'])
                    : null
            ]
        );
    }
    
    /**
     * @param CertificationType $certificationType
     * @param string            $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(CertificationType $certificationType, string $jwt) : Response
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
    
        $r = $this->requestPost(
            $this->host . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'certificationType' => $r['certificationType']
                    ? ModelFactory::createCertificationType($r['certificationType'])
                    : null
            ]
        );
    }
    
    /**
     * @param CertificationType $certificationType
     * @param string            $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(CertificationType $certificationType, string $jwt) : Response
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
    
        $r = $this->requestPut(
            $this->host . self::API_URL_PREFIX . '/' . $certificationType->getUid(),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response(
            $r['success'],
            $r['error']
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
    
        $r = $this->requestDelete(
            $this->host . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return (new Response(
            $r['success'],
            $r['error']
        ));
    }
}
