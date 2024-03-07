<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService 
{
    private HttpClientInterface $client;
    private string $apikey;
    public function __construct($apikey, HttpClientInterface $client)
    {
        $this->apikey = $apikey;
        $this->client = $client;
    }

    public function getWeather() :array {
        //Pour récupérer la météo courante, il nous faut une URL
        //Donc : requête sur l'API
        $response = $this->client->request(
            "GET",
            'https://api.openweathermap.org/data/2.5/weather?lon=1.44&lat=43.6&appid=cleapi' . $this->apikey

        );
        //transformer la réponse en tableau
        $response = $response->toArray();
        //retourne le tableau
        return $response;
    }
}