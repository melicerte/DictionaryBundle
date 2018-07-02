<?php

namespace spec\Knp\DictionaryBundle\DataFixtures;

use Knp\DictionaryBundle\Dictionary;
use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;
use PhpSpec\ObjectBehavior;

class DictionaryProviderSpec extends ObjectBehavior
{
    public function let(DictionaryRegistry $dictionaries)
    {
        $this->beConstructedWith($dictionaries);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\DataFixtures\DictionaryProvider');
    }

    public function it_returns_a_key_from_a_dictionary($dictionaries, Dictionary $dictionary)
    {
        $dictionaries->get('omg')->willReturn($dictionary);
        $dictionary->getKeys()->willReturn(['foo', 'bar', 'baz']);

        $value = $this->dictionary('omg');

        expect(['foo', 'bar', 'baz'])->toContain($value->getWrappedObject());
    }
}
