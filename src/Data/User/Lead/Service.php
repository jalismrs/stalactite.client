<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Lead;

use hunomina\DataValidator\Rule\Json\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
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
     * @throws RequestException
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
                    ->setJwt($jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
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
                    ->setUriParameters(
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
     * @throws RequestException
     * @throws ServiceException
     */
    public function addLeads(
        User $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        if ($userModel->getUid() === null) {
            throw new ServiceException(
                'User lacks a uid'
            );
        }
    
        try {
            $leads = ModelHelper::getUids(
                $leadModels,
                Post::class
            );
        } catch (InvalidArgumentException $invalidArgumentException) {
            $this
                ->getLogger()
                ->error($invalidArgumentException);
            
            throw new ServiceException(
                'Error while getting uids',
                $invalidArgumentException->getCode(),
                $invalidArgumentException
            );
        }
        
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/leads',
                    'POST'
                ))
                    ->setJson(
                        [
                            'leads' => $leads,
                        ]
                    )
                    ->setJwt($jwt)
                    ->setUriParameters(
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
     * @throws RequestException
     * @throws ServiceException
     */
    public function removeLeads(
        User $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        if ($userModel->getUid() === null) {
            throw new ServiceException(
                'User lacks a uid'
            );
        }
    
        try {
            $leads = ModelHelper::getUids(
                $leadModels,
                Post::class
            );
        } catch (InvalidArgumentException $invalidArgumentException) {
            $this
                ->getLogger()
                ->error($invalidArgumentException);
            
            throw new ServiceException(
                'Error while getting uids',
                $invalidArgumentException->getCode(),
                $invalidArgumentException
            );
        }
        
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/leads',
                    'DELETE'
                ))
                    ->setJson(
                        [
                            'leads' => $leads,
                        ]
                    )
                    ->setJwt($jwt)
                    ->setUriParameters(
                        [
                            $userModel->getUid(),
                        ]
                    )
            );
    }
}
