<?php
declare(strict_types=1);

namespace App\Validator\ImageExtension;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ImageExtension extends Constraint
{
    public $message = "invalid file format";
}