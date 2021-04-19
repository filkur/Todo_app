<?php
declare(strict_types=1);
namespace App\Validator\DeadlineData;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DeadlineData extends Constraint
{
    public $message = "You selected an earlier date than today!";
}

