<?php

namespace Knp\DictionaryBundle\Dictionary\Factory;


use Knp\DictionaryBundle\Dictionary\Factory;
use Knp\DictionaryBundle\Dictionary\SimpleTaggedDictionary;
use Knp\DictionaryBundle\Dictionary\SimpleDictionary;

abstract class AbstractSimpleFactory implements Factory
{
    protected function newInstance(string $name, array $values, array $tags = null)
    {
        if (!empty($tags)) {
            return new SimpleTaggedDictionary($name, $values, $tags);
        }

        return new SimpleDictionary($name, $values);
    }
}
