<?php

namespace Knp\DictionaryBundle\Dictionary;

class SimpleTaggedDictionary extends SimpleDictionary implements TaggedDictionaryInterface
{
    /**
     * @var string[]
     */
    private $tags;

    public function __construct(string $name, array $values, array $tags)
    {
        $this->tags = $tags;
        parent::__construct($name, $values);
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
