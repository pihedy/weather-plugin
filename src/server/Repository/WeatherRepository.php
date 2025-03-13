<?php

declare(strict_types=1);

namespace WPCM\Repository;

/**
 * Repository for retrieving and caching weather data.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class WeatherRepository
{
    /**
     * @param string $apiKey The API key used for retrieving weather data.
     */
    public function __construct(private string $apiKey)
    {
        /* Do Nothing. */
    }

    /**
     * Retrieves weather data for a specified city.
     *
     * @param string $city The name of the city to fetch weather data for.
     *
     * @return array Weather information for the specified city.
     *
     * @throws \Exception If there are issues fetching or caching weather data.
     */
    public function getWeatherByCity(string $city): array
    {
        $result = $this->getCacheByCity($city);

        if ($result !== false) {
            return $result;
        }

        $result = $this->fetchOpenWeatherData($city);

        if ($result['is_error']) {
            throw new \Exception($result['data']['message']);
        }

        if (!$this->setCacheByCity($city, $result['data'])) {
            throw new \Exception('Failed to set cache!');
        }

        return $result['data'];
    }

    /**
     * Fetches weather data from OpenWeatherMap API for a given city.
     *
     * @param string $city The name of the city to retrieve weather data for.
     *
     * @return array An array containing the API response, with 'is_error' and 'data' keys.
     */
    private function fetchOpenWeatherData(string $city): array
    {
        $data = [
            'is_error' => false,
            'data' => [],
        ];

        if ($city === '') {
            return [
                'is_error' => true,
                'data' => [
                    'message' => 'City is required!',
                ],
            ];
        }

        $url = sprintf(
            'https://api.openweathermap.org/data/2.5/weather?q=%s&appid=%s&units=metric',
            urlencode($city),
            $this->apiKey
        );

        $response = wp_remote_get($url);

        if (!isset($response['http_response']) || is_wp_error($response)) {
            return [
                'is_error' => true,
                'data' => [
                    'message' => 'Failed to fetch data!',
                ],
            ];
        }

        /**
         * @var \WP_HTTP_Requests_Response $response
         */
        $response = $response['http_response'];
        $body = json_decode($response->get_data(), true);

        if ($response->get_status() !== 200) {
            return [
                'is_error' => true,
                'data' => [
                    'message' => $body['message'] ?? 'Failed to fetch data!',
                ],
            ];
        }

        $data['data'] = $body;

        return $data;
    }

    /**
     * Retrieves cached weather data for a specific city.
     *
     * @param string $city The name of the city to retrieve cached weather data for.
     *
     * @return bool|array Cached weather data if available, or false if no cache exists.
     */
    private function getCacheByCity(string $city): bool|array
    {
        return get_transient('wpcm_' . $city);
    }

    /**
     * Stores weather data for a specific city in the transient cache.
     *
     * @param string $city The name of the city to cache weather data for.
     * @param array $data The weather data to be cached.
     *
     * @return bool Whether the cache was successfully set.
     */
    private function setCacheByCity(string $city, array $data): bool
    {
        return set_transient('wpcm_' . $city, $data, 900);
    }
}
