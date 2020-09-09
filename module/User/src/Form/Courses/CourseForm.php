<?php
declare(strict_types=1);
namespace User\Form\Courses;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Math\Rand;
 class CourseForm extends Form {
 	public function __construct($name=null){
		parent::__construct('course-form');
		$this->setAttribute('method','post');

		$this->add([
	        'name' => 'id',
	        'type' => 'hidden',
	    ]);

		$this->add([
			'type' => Element\Text::class,
			'name' => 'course',
			'options' => [
				'label' => 'Course Name',
			]
		]);

		// $this->add([
		// 	'type' => Element\Csrf::class,
		// 	'name' => 'csrf',
		// 	'options' => [
		// 		'csrf_options' => [
		// 			'timeout' => 600,
		// 		]
		// 	]
		// ]);

		$this->add([
	        'name' => 'submit',
	        'type' => 'submit',
	        'attributes' => [
	            'value' => 'Go',
	            'id'    => 'submitbutton',
	        ],
	    ]);
 	}
}