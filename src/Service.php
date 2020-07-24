<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

class Service extends AbstractService
{
    private ?Authentication\Service $serviceAuthentication = null;
    private ?Data\Service $serviceData = null;

    public function authentication(): Authentication\Service
    {
        if ($this->serviceAuthentication === null) {
            $this->serviceAuthentication = new Authentication\Service($this->getClient());
        }

        return $this->serviceAuthentication;
    }

    public function data(): Data\Service
    {
        if ($this->serviceData === null) {
            $this->serviceData = new Data\Service($this->getClient());
        }

        return $this->serviceData;
    }
}
