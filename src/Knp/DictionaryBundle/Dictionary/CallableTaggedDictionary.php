<?php

namespace Knp\DictionaryBundle\Dictionary;


class CallableTaggedDictionary extends CallableDictionary implements TaggedDictionaryInterface
{
    /**
     * @var string[]
     */
    private $tags;

    public function __construct(string $name, callable $callable, array $tags)
    {
        $this->tags = $tags;
        parent::__construct($name, $callable);
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
