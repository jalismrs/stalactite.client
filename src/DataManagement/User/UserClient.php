<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Client;
use jalismrs\Stalactite\Client\DataManagement\Model\User;

class UserClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/users';

    /** @var MeClient $meClient */
    private $meClient;

    /** @var PostClient $postClient */
    private $postClient;

    /** @var LeadClient $leadClient */
    private $leadClient;

    /** @var CertificationClient $certificationClient */
    private $certificationClient;

    /** @var PhoneClient $phoneClient */
    private $phoneClient;

    /**
     * @return MeClient
     */
    public function me(): MeClient
    {
        if (!($this->meClient instanceof MeClient)) {
            $this->meClient = new MeClient($this->apiHost, $this->userAgent);
            $this->meClient->setHttpClient($this->getHttpClient());
        }

        return $this->meClient;
    }

    /**
     * @return PostClient
     */
    public function posts(): PostClient
    {
        if (!($this->postClient instanceof PostClient)) {
            $this->postClient = new PostClient($this->apiHost, $this->userAgent);
            $this->postClient->setHttpClient($this->getHttpClient());
        }

        return $this->postClient;
    }

    /**
     * @return LeadClient
     */
    public function leads(): LeadClient
    {
        if (!($this->leadClient instanceof LeadClient)) {
            $this->leadClient = new LeadClient($this->apiHost, $this->userAgent);
            $this->leadClient->setHttpClient($this->getHttpClient());
        }

        return $this->leadClient;
    }

    /**
     * @return CertificationClient
     */
    public function certifications(): CertificationClient
    {
        if (!($this->certificationClient instanceof CertificationClient)) {
            $this->certificationClient = new CertificationClient($this->apiHost, $this->userAgent);
            $this->certificationClient->setHttpClient($this->getHttpClient());
        }

        return $this->certificationClient;
    }

    /**
     * @return PhoneClient
     */
    public function phones(): PhoneClient
    {
        if (!($this->phoneClient instanceof PhoneClient)) {
            $this->phoneClient = new PhoneClient($this->apiHost, $this->userAgent);
            $this->phoneClient->setHttpClient($this->getHttpClient());
        }

        return $this->phoneClient;
    }

    /**
     * @param string $jwt
     * @return array
     */
    public function getAll(string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     */
    public function get(User $user, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     */
    public function create(User $user, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     */
    public function update(User $user, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     */
    public function delete(User $user, string $jwt): array
    {

    }
}