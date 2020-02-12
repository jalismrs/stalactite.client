<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Lead;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
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
     * Service constructor.
     *
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'addLeads'    => [
                'endpoint' => '/data/users/%s/leads',
                'method'   => 'POST',
            ],
            'getAll'      => [
                'endpoint'   => '/data/users/%s/leads',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'leads' => array_map(
                            static function($lead) {
                                return ModelFactory::createPost($lead);
                            },
                            $response['leads']
                        ),
                    ];
                },
                'validation' => [
                    'leads' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => Schema::POST,
                    ],
                ],
            ],
            'removeLeads' => [
                'endpoint' => '/data/users/%s/leads',
                'method'   => 'DELETE',
            ],
        ];
    }
    
    /**
     * getAll
     *
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllLeads(
        User $userModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAll'],
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
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
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidArgumentException
     */
    public function addLeads(
        User $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['addLeads'],
                [
                    $userModel->getUid(),
                ],
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
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidArgumentException
     */
    public function removeLeads(
        User $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['removeLeads'],
                [
                    $userModel->getUid(),
                ],
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
            );
    }
}
