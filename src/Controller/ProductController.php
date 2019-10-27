<?php
namespace App\Controller;

use App\Entity\Product;
use App\Service\GetLatLong;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;


class ProductController extends EasyAdminController
{
  public function persistEntity($entity)
  {
      $addr=$entity->getAdress();
      $getlatlong=new GetLatLong();
      $geoloc=$getlatlong->indexAction($addr);
      $entityManager = $this->getDoctrine()->getManager();
      $entity->setLatitude($geoloc['latitude']);
      $entity->setLongitude($geoloc['longitude']);
      parent::persistEntity($entity);
  }

  public function updateEntity($entity)
  {
      $addr=$entity->getAdress();
      $id=$entity->getId();
      $getlatlong=new GetLatLong();
      $geoloc=$getlatlong->indexAction($addr);
      $entityManager = $this->getDoctrine()->getManager();
      $product = $entityManager->getRepository(Product::class)->findOneBy(array('id'=> $id));
      $product->setLatitude($geoloc['latitude']);
      $product->setLongitude($geoloc['longitude']);
      parent::updateEntity($entity);
  }

}