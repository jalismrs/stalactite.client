<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Lead;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Request;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User\Lead
 */
class Service extends
    AbstractService
{
    /**
     * getAllLeads
     *
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getAllLeads(
        User $userModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/leads'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'leads' => array_map(
                                    static function($lead) {
                                        return ModelFactory::createPost($lead);
                                    },
                                    $response['leads']
                                ),
                            ];
                        }
                    )
                    ->setUriDatas(
                        [
                            $userModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'leads' => [
                                'type'   => JsonRule::LIST_TYPE,
                                'schema' => Schema::POST,
                            ],
                        ]
                    )
            );
    }
    
    /**
     * addLeads
     *
     * @param User   $userModel
     * @param array  $leadModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function addLeads(
        User $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/leads'
                ))
                    ->setMethod('POST')
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => [
                                'leads' => ModelHelper::getUids(
                                    $leadModels,
                                    Post::class
                                )
                            ],
                        ]
                    )
                    ->setUriDatas(
                        [
                            $userModel->getUid(),
                        ]
                    )
            );
    }
    
    /**
     * removeLeads
     *
     * @param User   $userModel
     * @param array  $leadModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function removeLeads(
        User $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/leads'
                ))
                    ->setMethod('DELETE')
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => [
                                'leads' => ModelHelper::getUids(
                                    $leadModels,
                                    Post::class
                                )
                            ],
                        ]
                    )
                    ->setUriDatas(
                        [
                            $userModel->getUid(),
                        ]
                    )
            );
    }
}
