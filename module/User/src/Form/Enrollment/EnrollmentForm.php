<?php
declare(strict_types=1);
namespace User\Form\Enrollment;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Math\Rand;

class EnrollmentForm extends Form{

	protected $courses;
	protected $learners;

	public function __construct($courses, $learners){
		parent::__construct('enrollment-form');

		$this->courses  = $courses;
		$this->learners = $learners;

		$this->setAttribute('method','post');

		$this->add([
	        'name' => 'id',
	        'type' => 'hidden',
	    ]);


		$this->add([
			'type' => Element\Select::class,
			'name' => 'user_id',
			'options' => [
				'label' => 'Learner',
				'empty_option' => 'Select Learner',
				'value_options' => $this->learners ,
			],
			'attributes' => [
				'required' => true,
				'class' => 'custom-select',
			]
		]);

		$this->add([
			'type' => Element\Select::class,
			'name' => 'course_id',
			'options' => [
				'label' => 'Course',
				'empty_option' => 'Select Course',
				'value_options' => $this->courses,
			],
			'attributes' => [
				'required' => true,
				'class' => 'custom-select',
			]
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'enroll',
			'attributes' => [
				'value' => 'Enroll Learner',
				'class' => 'btn btn-primary'
			]
		]);

	}
}

?>