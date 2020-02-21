<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Customer;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Customer
 */
class Service extends
    AbstractService
{
    /**
     * @var Me\Service|null
     */
    private ?Me\Service $serviceMe = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * me
     *
     * @return Me\Service
     */
    public function me(): Me\Service
    {
        if ($this->serviceMe === null) {
            $this->serviceMe = new Me\Service($this->getClient());
        }

        return $this->serviceMe;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * getRelations
     *
     * @param Customer $customerModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getRelations(
        Customer $customerModel,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/customers/%s/relations'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response) use ($customerModel) : array {
                            return [
                                'relations' => array_map(
                                    static function (array $relation) use ($customerModel): DomainCustomerRelation {
                                        return ModelFactory::createDomainCustomerRelation($relation)
                                            ->setCustomer($customerModel);
                                    },
                                    $response['relations']
                                )
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $customerModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'relations' => [
                                'type' => JsonRule::LIST_TYPE,
                                'schema' => [
                                    'uid' => [
                                        'type' => JsonRule::STRING_TYPE
                                    ],
                                    'domain' => [
                                        'type' => JsonRule::OBJECT_TYPE,
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
     * @param Customer $customerModel
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getAccessClearance(
        Customer $customerModel,
        Domain $domainModel,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/customers/%s/access/%s'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'clearance' => ModelFactory::createAccessClearance($response['clearance']),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $customerModel->getUid(),
                            $domainModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'clearance' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'schema' => Schema::ACCESS_CLEARANCE
                            ]
                        ]
                    )
            );
    }
}
