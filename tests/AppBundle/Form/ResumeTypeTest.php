<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 07/12/17
 * Time: 14:37
 */

use AppBundle\Entity\Resume;
use AppBundle\Form\ResumeType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ResumeTypeTest extends TypeTestCase
{
    public function testSubmitWithSymfonyAndPositionValid()
    {
        $validator = Validation::createValidatorBuilder()
                               ->enableAnnotationMapping()
                               ->getValidator();
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
        $errors = $validator->validate($resume);
        $this->assertEquals(0, count($errors));
    }

    public function testSubmitWithPositionWithoutSymfony()
    {
        $validator = Validation::createValidatorBuilder()
                               ->enableAnnotationMapping()
                               ->getValidator();
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
        $errors = $validator->validate($resume);
        $this->assertNotEquals(0, count($errors));
    }

    public function testFormReturnsErrorWithBadData()
    {
        $validator = Validation::createValidatorBuilder()
                               ->enableAnnotationMapping()
                               ->getValidator();
        $formDatas = [
            'firstname' => '',
            'lastname'  => '',
            'age'       => 30,
            'symfony'   => false,
            'position'  => 'Symfony Developer',
            'comment'   => '',
            'test'      => '',
        ];
        $resume    = Resume::fromArray($formDatas);
        $form      = $this->factory->create(ResumeType::class);

        $form->submit($formDatas);
        $this->assertEquals($resume, $form->getData());
        $errors = $validator->validate($resume);
        $this->assertEquals(3, count($errors));
    }
}
