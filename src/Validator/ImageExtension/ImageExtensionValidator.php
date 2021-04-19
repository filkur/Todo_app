<?php
declare(strict_types=1);

namespace App\Validator\ImageExtension;

use App\Validator\ImageExtension\ImageExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


class ImageExtensionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof ImageExtension) {
            throw new UnexpectedTypeException($constraint, ImageExtension::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $allowed = array('jpg', 'JPG','jpeg','JPEG','png','PNG','GIF','gif');
        $ext = pathinfo($value,PATHINFO_EXTENSION);


        if (!in_array($ext,$allowed))
        {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}