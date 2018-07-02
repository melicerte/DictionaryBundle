<?php

namespace Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Dictionary as DictionaryInterface;
use Knp\DictionaryBundle\Exception\UnauthorizedActionOnDictionaryException;

class SimpleDictionary implements DictionaryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed[]|\ArrayAccess
     */
    private $values;

    /**
     * @param string               $name
     * @param mixed[]|\ArrayAccess $values
     */
    public function __construct($name, $values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys()
    {
        return \array_keys($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return \array_key_exists($offset, $this->values);
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->values[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new UnauthorizedActionOnDictionaryException('You can\'t modify or add a value inside a dictionary.');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new UnauthorizedActionOnDictionaryException('You can\'t remove something from a dictionary.');
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }
}
