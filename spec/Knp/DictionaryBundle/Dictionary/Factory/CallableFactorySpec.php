<?php

namespace spec\Knp\DictionaryBundle\Dictionary\Factory;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Container;

class CallableFactorySpec extends ObjectBehavior
{
    public function let(Container $container)
    {
        $this->beConstructedWith($container);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\Dictionary\Factory\CallableFactory');
    }

    public function it_is_a_factory()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\Dictionary\Factory');
    }

    public function it_supports_specific_config()
    {
        $this->supports(['type' => 'callable'])->shouldReturn(true);
    }

    public function it_creates_a_dictionary(
        $container,
        MockedService $service
    ) {
        $config = [
            'service' => 'service.id',
            'method' => 'getYolo',
        ];

        $container->get('service.id')->willReturn($service);
        $service->getYolo()->willReturn([
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ]);

        $dictionary = $this->create('yolo', $config);

        $dictionary->getName()->shouldBe('yolo');
        $dictionary->getValues()->shouldBe([
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ]);
    }

    public function it_creates_an_invokable_dictionary(
        $container,
        MockedService $service
    ) {
        $config = [
            'service' => 'service.id'
        ];

        $service = new CallableService();
        $container->get('service.id')->willReturn($service);
        
        $dictionary = $this->create('yolo', $config);

        $dictionary->getName()->shouldBe('yolo');
        $dictionary->getValues()->shouldBe([
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ]);
    }
}

class MockedService
{
    public function getYolo()
    {
    }
}

class CallableService
{
    public function __invoke()
    {
        return [
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ];
    }
}
