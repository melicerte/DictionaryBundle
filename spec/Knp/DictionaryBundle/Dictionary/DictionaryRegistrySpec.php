<?php

namespace spec\Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Dictionary;
use Knp\DictionaryBundle\Dictionary\TaggedDictionaryInterface;
use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;
use Knp\DictionaryBundle\Dictionary\SimpleTaggedDictionary;
use PhpSpec\ObjectBehavior;

class DictionaryRegistrySpec extends ObjectBehavior
{
    public function let(Dictionary $dictionary, Dictionary $dictionary2)
    {
        $dictionary->getName()->willReturn('foo');

        $this->add($dictionary);
        $this->set('dictionary', $dictionary2);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\Dictionary\DictionaryRegistry');
    }

    public function it_is_an_array_access()
    {
        $this->shouldHaveType('ArrayAccess');
    }

    public function it_is_iterable()
    {
        $this->shouldHaveType('IteratorAggregate');
    }

    public function it_is_countable()
    {
        $this->shouldHaveType('Countable');
    }

    public function it_provides_a_list_of_dictionaries($dictionary, $dictionary2)
    {
        $this->all()->shouldReturn([
            'foo' => $dictionary,
            'dictionary' => $dictionary2,
        ]);
    }

    public function it_sets_registry_entry($dictionary)
    {
        $this->set('bar', $dictionary)->shouldReturn($this);
    }

    public function it_should_throw_exception_if_entry_exists($dictionary)
    {
        $this->shouldThrow('\RuntimeException')->duringSet('foo', $dictionary);
    }

    public function it_should_entry_if_it_exists($dictionary)
    {
        $this->get('foo')->shouldReturn($dictionary);
    }

    public function it_should_throw_exception_if_entry_does_not_exist()
    {
        $this->shouldThrow('Knp\DictionaryBundle\Exception\DictionaryNotFoundException')->duringGet('bar');
    }

    public function its_offsetSet_method_cannot_be_called()
    {
        $this->shouldThrow('\RuntimeException')->duringOffsetSet('foo', 'bar');
    }

    public function its_offsetUnset_method_cannot_be_called()
    {
        $this->shouldThrow('\RuntimeException')->duringOffsetUnset('foo');
    }

    public function it_counts_entries()
    {
        $this->count()->shouldReturn(2);
    }

    public function it_provides_an_array_iterator($dictionary, $dictionary2)
    {
        $this->getIterator()->getArrayCopy()->shouldReturn([
            'foo' => $dictionary,
            'dictionary' => $dictionary2,
        ]);
    }

    public function it_filter_dictionaries(SimpleTaggedDictionary $dictionary3, SimpleTaggedDictionary $dictionary4)
    {
        $dictionary3->getName()->willReturn('hello');
        $dictionary4->getName()->willReturn('world');
        $this->set('hello', $dictionary3);
        $this->set('world', $dictionary4);

        $this->filter(function (Dictionary $dictionary, $name) {
            return $dictionary instanceof TaggedDictionaryInterface;
        })->shouldHaveCount(2);
    }

    public function it_filter_by_category(SimpleTaggedDictionary $dictionary3, SimpleTaggedDictionary $dictionary4, SimpleTaggedDictionary $dictionary5)
    {
        $dictionary3->getName()->willReturn('hello');
        $dictionary3->getTags()->willReturn(['main']);

        $dictionary4->getName()->willReturn('world');
        $dictionary4->getTags()->willReturn(['main']);

        $dictionary5->getName()->willReturn('PHP');
        $dictionary5->getTags()->willReturn(['external_world']);

        $this->set('hello', $dictionary3);
        $this->set('world', $dictionary4);
        $this->set('PHP', $dictionary5);

        $this->filterByTag('main')->shouldHaveCount(2);
        $this->filterByTag('external_world')->shouldHaveCount(1);
        $this->filterByTag('external_world')->shouldBeAnInstanceOf(DictionaryRegistry::class);
    }
}
