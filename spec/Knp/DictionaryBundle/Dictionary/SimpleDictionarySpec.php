<?php

namespace spec\Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Exception\UnauthorizedActionOnDictionaryException;
use PhpSpec\ObjectBehavior;

class SimpleDictionarySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('foo', [
            'foo' => 0,
            'bar' => 1,
            'baz' => 2,
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\Dictionary\SimpleDictionary');
    }

    public function it_is_a_dictionary()
    {
        $this->shouldImplement('Knp\DictionaryBundle\Dictionary');
    }

    public function its_getvalues_should_return_dictionary_values()
    {
        $this->getValues()->shouldReturn([
            'foo' => 0,
            'bar' => 1,
            'baz' => 2,
        ]);
    }

    public function its_getname_should_return_dictionary_name()
    {
        $this->getName()->shouldReturn('foo');
    }

    public function it_access_to_value_like_an_array()
    {
        expect($this['foo']->getWrappedObject())->toBe(0);
        expect($this['bar']->getWrappedObject())->toBe(1);
        expect($this['baz']->getWrappedObject())->toBe(2);
    }

    public function it_throws_exception_on_set_or_delete_item_user_action()
    {
        $this->shouldThrow(UnauthorizedActionOnDictionaryException::class)->duringOffsetSet('foo', 'bar');
        $this->shouldThrow(UnauthorizedActionOnDictionaryException::class)->duringOffsetUnset('baz');
    }
}
