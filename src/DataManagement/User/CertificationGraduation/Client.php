<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\CertificationGraduation;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduationModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\CertificationTypeModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\DataManagement\User\Client as ParentClient;
use function array_map;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\UserModel\CertificationGraduationModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = '/certifications';
    
    /**
     * @param UserModel $user
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(UserModel $user, string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'        => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'          => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'certifications' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::CERTIFICATION_GRADUATION
                ]
            ]
        );
    
        $response = $this->requestGet(
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART,
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
                'certifications' => array_map(
                    static function($certification) {
                        return ModelFactory::createCertificationGraduation($certification);
                    },
                    $response['certifications']
                )
            ]
        );
    }
    
    /**
     * add
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel                    $user
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduationModel $certificationGraduation
     * @param string                                                                        $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function add(
        UserModel $user,
        CertificationGraduationModel $certificationGraduation,
        string $jwt
    ) : Response
    {
        if (!$certificationGraduation->getType() instanceof CertificationTypeModel) {
            throw new ClientException(
                'Certification Graduation type must be a Certification Type',
                ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
            );
        }
        
        $body = [
            'date' => $certificationGraduation->getDate(),
            'type' => [
                'uid' => $certificationGraduation->getType()
                                                 ->getUid()
            ]
        ];
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
            ]
        );
    
        $response = $this->requestPost(
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART,
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
     * remove
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel                    $user
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduationModel $certificationGraduation
     * @param string                                                                        $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function remove(
        UserModel $user,
        CertificationGraduationModel $certificationGraduation,
        string $jwt
    ) : Response
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
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART . '/' . $certificationGraduation->getUid(),
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
