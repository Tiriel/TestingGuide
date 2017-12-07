<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 07/12/17
 * Time: 14:37
 */

use AppBundle\Entity\Resume;
use AppBundle\Form\ResumeType;
use AppBundle\Services\ResumeValidator;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Callback;

class ResumeTypeTest extends TypeTestCase
{
    public function testSubmitWithSymfonyAndPositionValid()
    {
        $validator = Validation::createValidator();
        $formDatas = [
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'age'       => 30,
            'symfony'   => true,
            'position'  => 'Symfony Developer',
            'comment'   => '',
        ];
        $resume    = Resume::fromArray($formDatas);
        $form      = $this->factory->create(ResumeType::class);

        $form->submit($formDatas);
        $this->assertEquals($resume, $form->getData());
        $errors = $validator->validate(
            $resume,
            new Callback(
                function ($object, $context, $payload) {
                    ResumeValidator::validate($object, $context, $payload);
                }
            )
        );
        $this->assertEquals(0, count($errors));
    }

    public function testSubmitWithPositionWithoutSymfony()
    {
        $validator = Validation::createValidator();
        $formDatas = [
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'age'       => 30,
            'symfony'   => false,
            'position'  => 'Symfony Developer',
            'comment'   => '',
        ];
        $resume    = Resume::fromArray($formDatas);
        $form      = $this->factory->create(ResumeType::class);

        $form->submit($formDatas);
        $this->assertEquals($resume, $form->getData());
        $errors = $validator->validate(
            $resume,
            new Callback(
                function ($object, $context, $payload) {
                    ResumeValidator::validate($object, $context, $payload);
                }
            )
        );
        $this->assertNotEquals(0, count($errors));
    }
}
