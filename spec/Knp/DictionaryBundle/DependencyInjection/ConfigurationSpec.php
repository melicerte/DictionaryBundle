<?php

namespace spec\Knp\DictionaryBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;

class ConfigurationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\DependencyInjection\Configuration');
    }

    public function it_generates_a_tree()
    {
        $this->getConfigTreeBuilder()->shouldHaveType('Symfony\Component\Config\Definition\Builder\TreeBuilder');
    }
}
