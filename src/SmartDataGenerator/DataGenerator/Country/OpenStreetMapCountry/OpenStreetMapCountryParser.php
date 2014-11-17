<?php


/**
 * @param string $countryName
 * @return array
 */
public function getCountryCoordinates($countryName)
{
    $url = sprintf(self::GEOLOCATION_URL, urlencode($countryName));
    $result = json_decode(file_get_contents($url), true);
    if (
        isset($result['results'][0]['geometry']['bounds']) &&
        isset($result['results'][0]['geometry']['location'])
    ) {
        $bounds = $result['results'][0]['geometry']['bounds'];
        $location = $result['results'][0]['geometry']['location'];
        return [
            'boundaries' => [
                'northeast' => [
                    'latitude' => (string)$bounds['northeast']['lat'],
                    'longitude' => (string)$bounds['northeast']['lng'],
                ],
                'southwest' => [
                    'latitude' => (string)$bounds['southwest']['lat'],
                    'longitude' => (string)$bounds['southwest']['lng'],
                ]
            ],
            'latitude' => (string)$location['lat'],
            'longitude' => (string)$location['lng']
        ];
    }

    return null;
}
