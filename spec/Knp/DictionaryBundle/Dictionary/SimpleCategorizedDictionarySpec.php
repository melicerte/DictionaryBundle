<?php

namespace spec\Knp\DictionaryBundle\Dictionary;

use Knp\DictionaryBundle\Dictionary\CategoryDictionaryInterface;
use Knp\DictionaryBundle\Dictionary\SimpleCategorizedDictionary;
use PhpSpec\ObjectBehavior;

class SimpleCategorizedDictionarySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(SimpleCategorizedDictionary::class);
        $this->shouldHaveType(CategoryDictionaryInterface::class);
    }

    public function let()
    {
        $this->beConstructedWith('foo', ['hello', 'world'], 'awesome_category');
    }

    public function it_gives_back_category()
    {
        $this->getCategory()->shouldBe('awesome_category');
    }
}
