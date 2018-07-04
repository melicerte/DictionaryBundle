<?php

namespace Knp\DictionaryBundle\Dictionary;

class SimpleCategorizedDictionary extends SimpleDictionary implements CategoryDictionaryInterface
{
    /**
     * @var string
     */
    private $category;

    public function __construct(string $name, array $values, string $category)
    {
        $this->category = $category;
        parent::__construct($name, $values);
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
