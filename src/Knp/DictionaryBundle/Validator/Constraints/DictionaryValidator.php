<?php

namespace Knp\DictionaryBundle\Validator\Constraints;

use Knp\DictionaryBundle\Dictionary\DictionaryRegistry;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DictionaryValidator extends ConstraintValidator
{
    /**
     * @var DictionaryRegistry
     */
    private $registry;

    /**
     * @param DictionaryRegistry $registry
     */
    public function __construct(DictionaryRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (false === $constraint instanceof Dictionary) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Dictionary');
        }

        if (empty($value)) {
            return;
        }

        $dictionary = $this->registry->get($constraint->name);
        $values = $dictionary->getKeys();

        if ($constraint->multiple && \is_array($value)) {
            $wrongValues = \array_diff($value, \array_intersect($values, $value));

            if (!empty($wrongValues)) {
                $this->context->addViolation(
                    $constraint->message,
                    ['{{ key }}' => \implode(', ', $wrongValues), '{{ keys }}' => \implode(', ', $values)]
                );
            }
        } elseif (false === \in_array($value, $values)) {
            $this->context->addViolation(
                $constraint->message,
                ['{{ key }}' => $value, '{{ keys }}' => \implode(', ', $values)]
            );
        }
    }
}
