<?php

namespace App\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class Utils
{
    /**
     * @param FormErrorIterator $errors
     * @return array
     */
    public static function errorsToArray(FormErrorIterator $errors): array
    {
        return array_map(function (FormError $error) {
            return [
                $error->getOrigin()->getName() => $error->getMessage()
            ];
        }, iterator_to_array($errors));
    }
}