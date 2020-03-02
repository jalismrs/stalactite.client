<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User\Me;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User\Me
 */
class Service extends
    AbstractService
{
    /**
     * getMe
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     */
    public function getMe(
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/me'
                ))
                    ->setJwt($jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'me' => $response['me'] === null
                                    ? null
                                    : ModelFactory::createUser($response['me']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'me' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
            );
    }
    
    /**
     * updateMe
     *
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws ServiceException
     */
    public function updateMe(
        User $userModel,
        string $jwt
    ): Response
    {
        if ($userModel->getUid() === null) {
            throw new ServiceException(
                'User lacks a uid'
            );
        }
    
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/me',
                    'PUT'
                ))
                    ->setJson($userModel)
                    ->setJwt($jwt)
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'update',
                            ],
                        ]
                    )
            );
    }
}
