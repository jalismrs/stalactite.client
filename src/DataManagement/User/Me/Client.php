<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\Me;

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
 * @package Jalismrs\Stalactite\Client\DataManagement\User\Me
 */
class Client extends
    AbstractClient
{
    public const API_URL_PREFIX = \Jalismrs\Stalactite\Client\DataManagement\User\Client::API_URL_PREFIX . '/me';
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $jwt) : Response
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
                ],
                'me'      => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
    
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'me' => $r['me']
                    ? ModelFactory::createUser($r['me'])
                    : null
            ]
        );
    }
    
    /**
     * @param User   $user
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(User $user, string $jwt) : Response
    {
        $body = [
            'firstName' => $user->getFirstName(),
            'lastName'  => $user->getLastName(),
            'office'    => $user->getOffice(),
            'birthday'  => $user->getBirthday()
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
                ]
            ]
        );
    
        $r = $this->requestPost(
            $this->host . self::API_URL_PREFIX,
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
     * @param PhoneLine $phoneLine
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addPhoneLine(PhoneLine $phoneLine, string $jwt) : Response
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
                ]
            ]
        );
    
        $r = $this->requestPost(
            $this->host . self::API_URL_PREFIX . '/phone/lines',
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
     * @param PhoneLine $phoneLine
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removePhoneLine(PhoneLine $phoneLine, string $jwt) : Response
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
            $this->host . self::API_URL_PREFIX . '/phone/lines/' . $phoneLine->getUid(),
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
