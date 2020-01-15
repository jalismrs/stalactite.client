<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\Lead;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\Post;
use Jalismrs\Stalactite\Client\DataManagement\Model\User;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\User\Lead
 */
class Client extends
    AbstractClient
{
    private const API_PARENT_URL_PREFIX = \Jalismrs\Stalactite\Client\DataManagement\User\Client::API_URL_PREFIX;
    public const API_URL_PREFIX = '/leads';
    
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
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'leads'   => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::POST
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
        
        $leads = [];
        foreach ($r['leads'] as $lead) {
            $leads[] = ModelFactory::createPost($lead);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'leads' => $leads
            ]
        );
    }
    
    /**
     * @param User   $user
     * @param array  $leads
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addLeads(User $user, array $leads, string $jwt) : Response
    {
        $body = ['leads' => []];
        
        foreach ($leads as $lead) {
            if (!$lead instanceof Post) {
                throw new ClientException(
                    '$leads array parameter must be a Post model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }
            
            if (null !== $lead->getUid()) {
                $body['leads'][] = $lead->getUid();
            }
        }
        
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
     * @param User   $user
     * @param array  $leads
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removeLeads(User $user, array $leads, string $jwt) : Response
    {
        $body = ['leads' => []];
        
        foreach ($leads as $lead) {
            if (!$lead instanceof Post) {
                throw new ClientException(
                    '$leads array parameter must be a Post model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }
            
            if (null !== $lead->getUid()) {
                $body['posts'][] = $lead->getUid();
            }
        }
        
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
}
