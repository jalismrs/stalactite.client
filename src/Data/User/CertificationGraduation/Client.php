<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\CertificationGraduation;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\CertificationGraduationModel;
use Jalismrs\Stalactite\Client\Data\Model\CertificationTypeModel;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\UserModel;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Data\User\Client as ParentClient;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\UserModel\CertificationGraduationModel
 */
class Client extends
    ClientAbstract
{
    /**
     * getAll
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel $userModel
     * @param string                                                     $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function getAll(
        UserModel $userModel,
        string $jwt
    ) : Response {
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
            vsprintf(
                '%s/data/users/%s/certifications',
                [
                    $this->host,
                    $userModel->getUid(),
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
                'certifications' => array_map(
                    static function($certification) {
                        return ModelFactory::createCertificationGraduationModel($certification);
                    },
                    $response['certifications']
                )
            ]
        );
    }
    
    /**
     * add
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel                    $userModel
     * @param \Jalismrs\Stalactite\Client\Data\Model\CertificationGraduationModel $certificationGraduationModel
     * @param string                                                                        $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function add(
        UserModel $userModel,
        CertificationGraduationModel $certificationGraduationModel,
        string $jwt
    ) : Response {
        if (!$certificationGraduationModel->getType() instanceof CertificationTypeModel) {
            throw new ClientException(
                'Certification Graduation type must be a Certification Type',
                ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
            );
        }
        
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
            vsprintf(
                '%s/data/users/%s/certifications',
                [
                    $this->host,
                    $userModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'date' => $certificationGraduationModel->getDate(),
                    'type' => [
                        'uid' => $certificationGraduationModel
                            ->getType()
                            ->getUid(),
                    ],
                ]
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
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel                    $userModel
     * @param \Jalismrs\Stalactite\Client\Data\Model\CertificationGraduationModel $certificationGraduationModel
     * @param string                                                                        $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function remove(
        UserModel $userModel,
        CertificationGraduationModel $certificationGraduationModel,
        string $jwt
    ) : Response {
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
                '%s/data/users/%s/certifications/%s',
                [
                    $this->host,
                    $userModel->getUid(),
                    $certificationGraduationModel->getUid(),
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
