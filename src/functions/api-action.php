<?php

declare(strict_types=1);

use WPCM\Repository\WeatherRepository;

/**
 * Registers a REST API route for retrieving weather information by city.
 */
add_action('rest_api_init', function(): void {
    register_rest_route('weather/v1', '/city/(?P<location>[a-zA-Z0-9_-]+)', [
        'methods' => 'GET',
        'callback' => 'getLoactionWeather',
    ]);
});

/**
 * Retrieves weather information for a specified location via WordPress REST API.
 *
 * @param WP_REST_Request $request The WordPress REST request object containing location parameter.
 *
 * @return void Sends JSON response with weather data or error status.
 */
function getLoactionWeather(WP_REST_Request $request): void
{
    $response = [
        'data' => [],
        'status' => ['message' => 'OK', 'code' => 200],
    ];

    try {
        $location = $request->get_param('location');

        if ($location === null) {
            throw new Exception('Location is required!');
        }

        $response['data'] = (new WeatherRepository(WPCM_OPENWEATHERMAP_API_KEY))->getWeatherByCity($location);
    } catch (Exception $e) {
        $response['status'] = ['message' => $e->getMessage(), 'code' => 500];
    }

    wp_send_json($response, $response['status']['code']);
}
