<?php

namespace spec\Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Dictionary\TaggedDictionaryInterface;
use Knp\DictionaryBundle\Dictionary\SimpleTaggedDictionary;
use PhpSpec\ObjectBehavior;

class SimpleTaggedDictionarySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(SimpleTaggedDictionary::class);
        $this->shouldHaveType(TaggedDictionaryInterface::class);
    }

    public function let()
    {
        $this->beConstructedWith('foo', ['hello', 'world'], ['awesome_category']);
    }

    public function it_gives_back_category()
    {
        $this->getTags()->shouldBe(['awesome_category']);
    }
}
