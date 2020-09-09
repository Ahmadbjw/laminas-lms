<?php

namespace User\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;

class Course
{
    public $id;
    public $course;

    public function exchangeArray(array $data)
    {
        $this->id      = !empty($data['id']) ? $data['id'] : null;
        $this->course  = !empty($data['course']) ? $data['course'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'course' => $this->course,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter(){

    	$inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

    	 $inputFilter->add([
            'name' => 'course',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

    	$this->inputFilter = $inputFilter;
    	return $this->inputFilter;
    }	
}