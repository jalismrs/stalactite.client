<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\Lead;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\PostModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;
use \Jalismrs\Stalactite\Client\DataManagement\User\Client as ParentClient;
use function array_map;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\UserModel\Lead
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = '/leads';
    
    /**
     * @param UserModel $user
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(UserModel $user, string $jwt) : Response
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
    
        $response = $this->requestGet(
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        // TODO: vérifier pouruqoi createLead n'est pas utilisé
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'leads' => array_map(
                    static function($lead) {
                        return ModelFactory::createPost($lead);
                    },
                    $response['leads']
                )
            ]
        );
    }
    
    /**
     * @param UserModel $user
     * @param array     $leads
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addLeads(UserModel $user, array $leads, string $jwt) : Response
    {
        $body = [
            'leads' => []
        ];
        
        foreach ($leads as $lead) {
            if (!$lead instanceof PostModel) {
                throw new ClientException(
                    '$leads array parameter must be a PostModel model array',
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
    
        $response = $this->requestPost(
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
    
    /**
     * @param UserModel $user
     * @param array     $leads
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removeLeads(UserModel $user, array $leads, string $jwt) : Response
    {
        $body = [
            'leads' => []
        ];
        
        foreach ($leads as $lead) {
            if (!$lead instanceof PostModel) {
                throw new ClientException(
                    '$leads array parameter must be a PostModel model array',
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
    
        $response = $this->requestDelete(
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
