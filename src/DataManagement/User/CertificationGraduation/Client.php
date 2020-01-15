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

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\UserModel\CertificationGraduationModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PREFIX = '/certifications';
    
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
    
        $r = $this->requestGet(
            $this->host . ParentClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        $certifications = [];
        foreach ($r['certifications'] as $certification) {
            $certifications[] = ModelFactory::createCertificationGraduation($certification);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'certifications' => $certifications
            ]
        );
    }
    
    /**
     * @param UserModel                    $user
     * @param CertificationGraduationModel $certificationGraduation
     * @param string                       $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addCertification(
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
    
        $r = $this->requestPost(
            $this->host . ParentClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX,
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
     * @param UserModel                    $user
     * @param CertificationGraduationModel $certificationGraduation
     * @param string                       $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removeCertification(
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
    
        $r = $this->requestDelete(
            $this->host . ParentClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX . '/' . $certificationGraduation->getUid(),
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
