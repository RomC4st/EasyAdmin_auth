<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class JsonTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
      return implode(',',$value);    
    }

    public function reverseTransform($value)
    {
        return explode(',',$value);
    }
}