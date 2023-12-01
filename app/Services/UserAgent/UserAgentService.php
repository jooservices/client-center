<?php

namespace App\Services\UserAgent;

use Exception;
use Illuminate\Support\Collection;

class UserAgentService
{
    private Collection $agentDetails;

    public function __construct()
    {
        $this->agentDetails = collect(
            json_decode(
                file_get_contents(
                    __DIR__ . '/agents.json'),
                true
            )
        );
    }

    /**
     * @param array $filterBy
     * @return array
     * @throws Exception
     */
    protected function filter(array $filterBy = []): array
    {
        $filterBy = $this->validateFilter($filterBy);

        return $this->agentDetails->filter(function ($item) use ($filterBy) {
            foreach ($filterBy as $key => $value) {
                if ($item[$key] !== $value) {
                    return false;
                }
            }

            return true;
        })->toArray();
    }

    /**
     * Grab a random user agent from the library's agent list
     *
     * @param array $filterBy
     * @return string
     * @throws Exception
     */
    public function random(array $filterBy = [])
    {
        $filtered = $this->filter($filterBy);

        if (empty($filtered)) {
            throw new Exception('No user agents found for the given filter');
        }

        return $filtered[array_rand($filtered)];
    }

    /**
     * Get all of the unique values of the device_type field, which can be used for filtering
     * Device types give a general description of the type of hardware that the agent is running,
     * such as "Desktop", "Tablet", or "Mobile"
     *
     * @return array
     * @throws Exception
     */
    public function getDeviceTypes(): array
    {
        return $this->getField('device_type');
    }

    /**
     * Get all of the unique values of the agent_type field, which can be used for filtering
     * Agent types give a general description of the type of software that the agent is running,
     * such as "Crawler" or "Browser"
     *
     * @return array
     */
    public function getAgentTypes(): array
    {
        return $this->getField('agent_type');
    }

    /**
     * Get all of the unique values of the agent_name field, which can be used for filtering
     * Agent names are general identifiers for a given user agent. For example, "Chrome" or "Firefox"
     *
     * @return array
     */
    public function getAgentNames(): array
    {
        return $this->getField('agent_name');
    }

    /**
     * Get all of the unique values of the os_type field, which can be used for filtering
     * OS Types are general names given for an operating system, such as "Windows" or "Linux"
     *
     * @return array
     */
    public function getOSTypes(): array
    {
        return $this->getField('os_type');
    }

    /**
     * Get all of the unique values of the os_name field, which can be used for filtering
     * OS Names are more specific names given to an operating system, such as "Windows Phone OS"
     *
     * @return array
     */
    public function getOSNames(): array
    {
        return $this->getField('os_name');
    }

    /**
     * This is a helper for the publicly-exposed methods named get...()
     * @param string $fieldName
     * @return array
     * @throws Exception
     */
    private function getField($fieldName): array
    {
        return $this->agentDetails->pluck($fieldName)->unique()->values()->toArray();
    }

    /**
     * Validates the filter so that no unexpected values make their way through
     *
     * @param array $filterBy
     * @return array
     */
    private function validateFilter(array $filterBy = []): array
    {
        // Components of $filterBy that will not be ignored
        $filterParams = [
            'agent_name',
            'agent_type',
            'device_type',
            'os_name',
            'os_type',
        ];

        $outputFilter = [];

        foreach ($filterParams as $field) {
            if (!empty($filterBy[$field])) {
                $outputFilter[$field] = $filterBy[$field];
            }
        }

        return $outputFilter;
    }
}
