<?php

namespace Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Dictionary;

interface CategoryDictionaryInterface extends Dictionary
{
    public function getCategory(): string;
}
