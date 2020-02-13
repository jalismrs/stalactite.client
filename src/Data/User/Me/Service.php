<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Me;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
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
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getMe(
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/me'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function(array $response) : array {
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
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
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
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function updateMe(
        User $userModel,
        string $jwt
    ) : Response {
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
