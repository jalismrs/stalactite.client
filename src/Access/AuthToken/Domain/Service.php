<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Domain;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Access\ResponseFactory;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\Domain
 */
class Service extends
    AbstractService
{
    /**
     * deleteRelationsByDomain
     *
     * @param Domain $domainModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws ServiceException
     */
    public function deleteRelationsByDomain(
        Domain $domainModel,
        string $apiAuthToken
    ): Response
    {
        if ($domainModel->getUid() === null) {
            throw new ServiceException(
                'Domain lacks a uid'
            );
        }
    
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
                    '/access/auth-token/domains/%s/relations',
                    'DELETE'
                ))
                    ->setJwt((string)$jwt)
                    ->setUriParameters(
                        [
                            $domainModel->getUid(),
                        ]
                    )
            );
    }

    /**
     * getRelations
     *
     * @param Domain $domainModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     */
    public function getRelations(
        Domain $domainModel,
        string $apiAuthToken
    ): Response
    {
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
                    '/access/auth-token/domains/%s/relations'
                ))
                    ->setJwt((string)$jwt)
                    ->setResponseFormatter(ResponseFactory::domainGetRelations($domainModel))
                    ->setUriParameters(
                        [
                            $domainModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'relations' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'schema' => [
                                    'users' => [
                                        'type' => JsonRule::LIST_TYPE,
                                        'schema' => [
                                            'uid' => [
                                                'type' => JsonRule::STRING_TYPE,
                                            ],
                                            'user' => [
                                                'type' => JsonRule::OBJECT_TYPE,
                                                'schema' => DataSchema::USER,
                                            ],
                                        ],
                                    ],
                                    'customers' => [
                                        'type' => JsonRule::LIST_TYPE,
                                        'schema' => [
                                            'uid' => [
                                                'type' => JsonRule::STRING_TYPE
                                            ],
                                            'customer' => [
                                                'type' => JsonRule::OBJECT_TYPE,
                                                'schema' => DataSchema::CUSTOMER,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    )
            );
    }
}
