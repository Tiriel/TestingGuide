<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 07/12/17
 * Time: 16:28
 */

namespace AppBundle\Services;


use AppBundle\Entity\Resume;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResumeValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        $symfony = [
            'Fullstack Developer',
            'Symfony Developer',
            'Roxxor',
        ];

        if ($object instanceof Resume && in_array($object->getPosition(), $symfony) && ! $object->getSymfony()) {
            $context->buildViolation('You must know Symfony to apply for this position.')
                    ->atPath('position')
                    ->addViolation();
        }
    }
}