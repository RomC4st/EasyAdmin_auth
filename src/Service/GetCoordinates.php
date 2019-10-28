<?php

namespace App\Service;

use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Geocoder\Provider\LocationIQ\LocationIQ;

class GetCoordinates
{
    public function indexAction($addr)
    {
      try {
        $geocoder = new \Geocoder\ProviderAggregator();
        $adapter  = new \Http\Adapter\Guzzle6\Client();
        $apiKey=$_ENV['APP_PROVIDER_GEOCODER_KEY'];
        $geocoder->registerProviders([
            new \Geocoder\Provider\LocationIQ\LocationIQ($adapter,$apiKey)
        ]);   

        $result = $geocoder->geocodeQuery(GeocodeQuery::create($addr));
        $coordinates= $result->get(0)->getCoordinates();
        $latitude = $coordinates->getLatitude(); 
        $longitude = $coordinates->getLongitude();

        $geoloc= array('latitude'=>$latitude,'longitude'=>$longitude);
        return $geoloc;
      }catch(Exception $e){
        throw new Exception($e->getMessage());
      }
     
    }
}