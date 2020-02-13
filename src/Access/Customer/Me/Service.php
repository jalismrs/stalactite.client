<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Customer\Me;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Client\Access\Customer\Me
 */
class Service extends
    AbstractService
{
    /**
     * getRelations
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws SerializerException
     * @throws RequestException
     */
    public function getRelations(
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/customers/me/relations'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'relations' => array_map(
                                    static function(array $relation) : DomainCustomerRelation {
                                        return ModelFactory::createDomainCustomerRelation($relation);
                                    },
                                    $response['relations']
                                ),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'relations' => [
                                'type'   => JsonRule::LIST_TYPE,
                                'schema' => [
                                    'uid'    => [
                                        'type' => JsonRule::STRING_TYPE
                                    ],
                                    'domain' => [
                                        'type'   => JsonRule::OBJECT_TYPE,
                                        'schema' => DataSchema::DOMAIN
                                    ]
                                ]
                            ]
                        ]
                    )
            );
    }
    
    /**
     * getAccessClearance
     *
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws SerializerException
     * @throws RequestException
     */
    public function getAccessClearance(
        Domain $domainModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/customers/me/access/%s'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'clearance' => ModelFactory::createAccessClearance($response['clearance']),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $domainModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'clearance' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'schema' => Schema::ACCESS_CLEARANCE
                            ]
                        ]
                    )
            );
    }
}
