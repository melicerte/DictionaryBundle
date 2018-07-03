<?php

namespace spec\Knp\DictionaryBundle\Dictionary\Factory;

use Knp\DictionaryBundle\Dictionary\SimpleCategorizedDictionary;
use Knp\DictionaryBundle\Dictionary\ValueTransformer;
use PhpSpec\ObjectBehavior;

class KeyValueSpec extends ObjectBehavior
{
    public function let(ValueTransformer $transformer)
    {
        $this->beConstructedWith($transformer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\Dictionary\Factory\KeyValue');
    }

    public function it_is_a_factory()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\Dictionary\Factory');
    }

    public function it_supports_specific_config()
    {
        $this->supports(['type' => 'key_value'])->shouldReturn(true);
    }

    public function it_throws_exception_if_no_content_is_provided()
    {
        $this
            ->shouldThrow('\InvalidArgumentException')
            ->during('create', ['yolo', []])
        ;
    }

    public function it_creates_a_dictionary($transformer)
    {
        $config = [
            'content' => [
                'foo1' => 'bar1',
                'foo2' => 'bar2',
                'foo3' => 'bar3',
            ],
        ];

        $transformer->transform('bar1')->willReturn('bar1');
        $transformer->transform('bar2')->willReturn('bar2');
        $transformer->transform('bar3')->willReturn('bar3');
        $transformer->transform('foo1')->willReturn('foo1');
        $transformer->transform('foo2')->willReturn('foo2');
        $transformer->transform('foo3')->willReturn('foo3');

        $dictionary = $this->create('yolo', $config);

        $dictionary->getName()->shouldBe('yolo');
        $dictionary->getValues()->shouldBe([
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ]);
    }

    public function it_creates_a_categorized_dictionary($transformer)
    {
        $config = [
            'category' => 'some',
            'content' => [
                'foo1' => 'bar1',
                'foo2' => 'bar2',
                'foo3' => 'bar3',
            ],
        ];

        $transformer->transform('bar1')->willReturn('bar1');
        $transformer->transform('bar2')->willReturn('bar2');
        $transformer->transform('bar3')->willReturn('bar3');
        $transformer->transform('foo1')->willReturn('foo1');
        $transformer->transform('foo2')->willReturn('foo2');
        $transformer->transform('foo3')->willReturn('foo3');

        $dictionary = $this->create('hello', $config);

        $dictionary->shouldBeAnInstanceOf(SimpleCategorizedDictionary::class);
    }
}
