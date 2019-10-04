<?php

namespace spec\Knp\DictionaryBundle\Validator\Constraints;

use Knp\DictionaryBundle\Dictionary;
use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;
use Knp\DictionaryBundle\Validator\Constraints\Dictionary as Constraint;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DictionaryValidatorSpec extends ObjectBehavior
{
    public function let(DictionaryRegistry $registry, ExecutionContextInterface $context, Dictionary $dictionary)
    {
        $this->beConstructedWith($registry);
        $this->initialize($context);

        $registry->get('dico')->willReturn($dictionary);

        $dictionary->getKeys()->willReturn(['the_key', 'the_other_key']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Knp\DictionaryBundle\Validator\Constraints\DictionaryValidator');
    }

    public function it_valids_existing_keys($context)
    {
        $constraint = new Constraint(['name' => 'dico']);

        $context->addViolation(Argument::any())->shouldNotBeCalled();

        $this->validate('the_key', $constraint);
    }

    public function it_adds_violation_for_an_unexisting_keys($context)
    {
        $constraint = new Constraint(['name' => 'dico']);

        $context->addViolation('The key(s) {{ key }} doesn\'t exist in the given dictionary. {{ keys }} available.', ['{{ key }}' => 'the_unexisting_key', '{{ keys }}' => 'the_key, the_other_key'])->shouldBeCalled();

        $this->validate('the_unexisting_key', $constraint);
    }

    public function it_adds_violation_string_value_with_dictionary_containing_0(DictionaryRegistry $registry, ExecutionContextInterface $context, Dictionary $dictionary)
    {
        $this->initialize($context);

        $registry->get('dico')->willReturn($dictionary);

        $dictionary->getKeys()->willReturn([0, 1]);
        $constraint = new Constraint(['name' => 'dico']);

        $context->addViolation('The key(s) {{ key }} doesn\'t exist in the given dictionary. {{ keys }} available.', ['{{ key }}' => 'a not existing key', '{{ keys }}' => '0, 1'])->shouldBeCalled();

        $this->validate('a not existing key', $constraint);
    }

    public function it_throw_exception_form_unknown_constraints()
    {
        $constraint = new NotNull();
        $this->shouldThrow(new UnexpectedTypeException($constraint, 'Knp\DictionaryBundle\Validator\Constraints\Dictionary'))->duringValidate('the_key', $constraint);
    }

    public function it_does_nothing_when_empty_value($context)
    {
        $constraint = new Constraint(['name' => 'dico']);

        $context->addViolation(Argument::any())->shouldNotBeCalled();

        $this->validate('', $constraint);
        $this->validate([], $constraint);
    }

    public function it_should_add_violation_on_array_with_error($context)
    {
        $constraint = new Constraint(['name' => 'dico', 'multiple' => true]);

        $context->addViolation('The key(s) {{ key }} doesn\'t exist in the given dictionary. {{ keys }} available.', ['{{ key }}' => 'the_unexisting_key, other_wrong_key', '{{ keys }}' => 'the_key, the_other_key'])->shouldBeCalled();

        $this->validate(['the_key', 'the_unexisting_key', 'other_wrong_key'], $constraint);
    }

    public function it_should_not_add_violation_on_correct_array($context)
    {
        $constraint = new Constraint(['name' => 'dico', 'multiple' => true]);

        $context->addViolation(Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->validate(['the_key', 'the_other_key'], $constraint);
    }

    public function it_should_not_accept_array_if_multiple_not_defined($context)
    {
        $constraint = new Constraint(['name' => 'dico', 'multiple' => false]);

        $context->addViolation('The key(s) {{ key }} doesn\'t exist in the given dictionary. {{ keys }} available.', ['{{ key }}' => ['the_key', 'the_other_key'], '{{ keys }}' => 'the_key, the_other_key'])->shouldBeCalled();

        $this->validate(['the_key', 'the_other_key'], $constraint);
    }
}
