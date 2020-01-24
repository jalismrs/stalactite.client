<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User\CertificationGraduation;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\CertificationGraduation;
use Jalismrs\Stalactite\Client\Data\Model\CertificationType;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\User\CertificationGraduation
 */
class Client extends
    AbstractClient
{
    /**
     * getAll
     *
     * @param User $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllCertificationGraduations(
        User $userModel,
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
                'certifications' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::CERTIFICATION_GRADUATION
                ]
            ]
        );

        $response = $this->get(
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
                    static function ($certification) {
                        return ModelFactory::createCertificationGraduationModel($certification);
                    },
                    $response['certifications']
                )
            ]
        );
    }

    /**
     * addCertificationGraduation
     *
     * @param User $userModel
     * @param CertificationGraduation $certificationGraduationModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addCertificationGraduation(
        User $userModel,
        CertificationGraduation $certificationGraduationModel,
        string $jwt
    ): Response
    {
        if (!$certificationGraduationModel->getType() instanceof CertificationType) {
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
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
            ]
        );

        $response = $this->post(
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
                'json' => [
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
     * removeCertificationGraduation
     *
     * @param User $userModel
     * @param CertificationGraduation $certificationGraduationModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removeCertificationGraduation(
        User $userModel,
        CertificationGraduation $certificationGraduationModel,
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

        $response = $this->delete(
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
