<?php

namespace User\Model;

use Carbon\Carbon;
use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\Digits;
use Laminas\Validator\StringLength;

class Enrollment
{
    public $id;
    public $course_id;
    public $user_id;
    public $created_at;

    public function exchangeArray(array $data)
    {
        $this->id         = !empty($data['id']) ? $data['id'] : null;
        $this->user_id    = !empty($data['user_id']) ? $data['user_id'] : null;
        $this->course_id  = !empty($data['course_id']) ? $data['course_id'] : null;
        $this->created_at = !empty($data['created_at']) ? (new Carbon)->toDayDateTimeString($data['created_at']) : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'course_id'  => $this->course_id,
            'created_at' => $this->created_at
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
            'name' => 'user_id',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => ToInt::class],
            ],
            'validators' => [
                [
                    'name' => IsInt::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                    ],
                ],
            ],
        ]);

         $inputFilter->add([
            'name' => 'course_id',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => ToInt::class],
            ],
            'validators' => [
                [
                    'name' => IsInt::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                    ],
                ],
            ],
        ]);

    	$this->inputFilter = $inputFilter;
    	return $this->inputFilter;
    }	
}