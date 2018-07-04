<?php

namespace Knp\DictionaryBundle\Dictionary;


class CallableCategorizedDictionary extends CallableDictionary implements CategoryDictionaryInterface
{
    /**
     * @var string
     */
    private $category;

    public function __construct(string $name, $callable, string $category)
    {
        $this->category = $category;
        parent::__construct($name, $callable);
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
