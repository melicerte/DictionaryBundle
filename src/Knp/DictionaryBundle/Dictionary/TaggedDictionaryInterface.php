<?php

namespace Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Dictionary;

interface TaggedDictionaryInterface extends Dictionary
{
    public function getTags(): array;
}
