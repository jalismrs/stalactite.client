<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\PhoneLine;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;
use Jalismrs\Stalactite\Client\DataManagement\Model\User;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\User\PhoneLine
 */
class Client extends
    AbstractClient
{
    private const API_PARENT_URL_PREFIX = \Jalismrs\Stalactite\Client\DataManagement\User\Client::API_URL_PREFIX;
    
    public const API_URL_PREFIX = '/phone/lines';
    
    /**
     * @param User   $user
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(User $user, string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'    => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'      => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'phoneLines' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::PHONE_LINE
                ]
            ]
        );
        
        $r = $this->requestGet(
            $this->host . self::API_PARENT_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        $phoneLines = [];
        foreach ($r['phoneLines'] as $phoneLine) {
            $phoneLines[] = ModelFactory::createPhoneLine($phoneLine);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'phoneLines' => $phoneLines
            ]
        );
    }
    
    /**
     * @param User      $user
     * @param PhoneLine $phoneLine
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addPhoneLine(User $user, PhoneLine $phoneLine, string $jwt) : Response
    {
        if (!$phoneLine->getType() instanceof PhoneType) {
            throw new ClientException(
                'Phone Line type must be a Phone Type',
                ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
            );
        }
        
        $body = [
            'value' => $phoneLine->getValue(),
            'type'  => [
                'uid' => $phoneLine->getType()
                                   ->getUid()
            ]
        ];
        
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
        
        $r = $this->requestPost(
            $this->host . self::API_PARENT_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response(
            $r['success'],
            $r['error']
        ));
    }
    
    /**
     * @param User      $user
     * @param PhoneLine $phoneLine
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removePhoneLine(User $user, PhoneLine $phoneLine, string $jwt) : Response
    {
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
        
        $r = $this->requestDelete(
            $this->host . self::API_PARENT_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX . '/' . $phoneLine->getUid(),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return (new Response(
            $r['success'],
            $r['error']
        ));
    }
}
