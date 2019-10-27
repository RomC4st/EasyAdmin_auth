<?php

namespace App\Service;

use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Geocoder\Provider\LocationIQ\LocationIQ;

class GetLatLong
{
    public function indexAction($addr)
    {
      $geocoder = new \Geocoder\ProviderAggregator();
      $adapter  = new \Http\Adapter\Guzzle6\Client();
      $apiKey=$_ENV['API_LOCATIONIQ_KEY'];
        $geocoder->registerProviders([
          new \Geocoder\Provider\LocationIQ\LocationIQ($adapter,$apiKey)
      ]);   
      $result = $geocoder->geocodeQuery(GeocodeQuery::create($addr));
      $coordinates= $result->get(0)->getCoordinates();
      $latitude = $coordinates->getLatitude(); 
      $longitude = $coordinates->getLongitude();
      $geoloc= array('latitude'=>$latitude,'longitude'=>$longitude);
        return $geoloc;
    }
}