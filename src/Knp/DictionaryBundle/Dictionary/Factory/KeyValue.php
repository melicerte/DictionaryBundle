<?php

namespace Knp\DictionaryBundle\Dictionary\Factory;

use Knp\DictionaryBundle\Dictionary\Factory;
use Knp\DictionaryBundle\Dictionary\SimpleDictionary;
use Knp\DictionaryBundle\Dictionary\ValueTransformer;

class KeyValue extends AbstractSimpleFactory
{
    /**
     * @var ValueTransformer
     */
    protected $transformer;

    /**
     * @param ValueTransformer $transformer
     */
    public function __construct(ValueTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function create($name, array $config)
    {
        if (!isset($config['content'])) {
            throw new \InvalidArgumentException(\sprintf(
                'The key content for dictionary %s must be set',
                $name
            ));
        }

        $content = $config['content'];
        $values = [];

        foreach ($content as $key => $value) {
            $builtValue = $this->transformer->transform($value);
            $key = $this->transformer->transform($key);
            $values[$key] = $builtValue;
        }

        return $this->newInstance($name, $values, $config['tags'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $config)
    {
        return (isset($config['type'])) ? 'key_value' === $config['type'] : false;
    }
}
