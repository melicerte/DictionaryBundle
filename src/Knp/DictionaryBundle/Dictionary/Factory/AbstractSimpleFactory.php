<?php

namespace Knp\DictionaryBundle\Dictionary\Factory;


use Knp\DictionaryBundle\Dictionary\Factory;
use Knp\DictionaryBundle\Dictionary\SimpleCategorizedDictionary;
use Knp\DictionaryBundle\Dictionary\SimpleDictionary;

abstract class AbstractSimpleFactory implements Factory
{
    protected function newInstance(string $name, array $values, string $category = null)
    {
        if (isset($category)) {
            return new SimpleCategorizedDictionary($name, $values, $category);
        }

        return new SimpleDictionary($name, $values);
    }
}
