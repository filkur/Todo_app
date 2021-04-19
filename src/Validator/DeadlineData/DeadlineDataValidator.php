<?php
declare(strict_types=1);
namespace App\Validator\DeadlineData;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DeadlineDataValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof DeadlineData) {
            throw new UnexpectedTypeException($constraint, DeadlineData::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($value <= new \DateTime()) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation()
            ;
        }
    }
}