<?php
declare(strict_types=1);
namespace User\Form\Auth;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Math\Rand;


class CreateForm extends Form{

	public function __construct(){
		parent::__construct('new_account');
		$this->setAttribute('method','post');

		$this->add([
	        'name' => 'id',
	        'type' => 'hidden',
	    ]);


		$this->add([
			'type' => Element\Text::class,
			'name' => 'first_name',
			'options' => [
				'label' => 'First Name',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'pattern' => '[a-zA-Z]+$',
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'First Name must consist of alhabets only',
				'placeholder' => 'Enter your First Name',
			]

		]);

		$this->add([
			'type' => Element\Text::class,
			'name' => 'last_name',
			'options' => [
				'label' => 'Last Name',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'pattern' => '[a-zA-Z]+$',
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Last Name must consist of alhabets only',
				'placeholder' => 'Enter your Last Name',
			]

		]);

		$this->add([
			'type' => Element\Text::class,
			'name' => 'username',
			'options' => [
				'label' => 'Username'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'pattern' => '^[a-zA-Z0-9]+$',
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'username must consist of alphanumeric characters only',
				'placeholder' => 'Enter username'
			]

		]);
		#Gender
		$this->add([
			'type' => Element\Select::class,
			'name' => 'gender',
			'options' => [
				'label' => 'Gender',
				'empty_option' => 'Select...',
				'value_options' => [
					'F' => 'Female',
					'M' => 'Male',
					'O' => 'Other'

				] ,
			],
			'attributes' => [
				'required' => true,
				'class' => 'custom-select',
			]
		]);

		$this->add([
			'type' => Element\Email::class,
			'name' => 'email',
			'options' => [
				'label' => 'Email Address'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Provide valid and working Email Address',
				'placeholder' => 'Enter Your Email Address'
			]

		]);
#Confirm Email address
			$this->add([
			'type' => Element\Email::class,
			'name' => 'confirm_email',
			'options' => [
				'label' => 'Verify Email Address',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Email Address must match that provide above',
				'placeholder' => 'Enter Your Email Address again'
			]

		]);

			$this->add([
			'type' => Element\Password::class,
			'name' => 'password',
			'options' => [
				'label' => 'Password'
			],
			'attributes' => [
				'required' => true,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Password must have between 8 and 25 chracters',
				'placeholder' => 'Enter Your password'
			]

		]);
#confirm password input field
			$this->add([
			'type' => Element\Password::class,
			'name' => 'confirm_password',
			'options' => [
				'label' => 'Verify Password'
			],
			'attributes' => [
				'required' => true,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Password must matach that provided above',
				'placeholder' => 'Enter Your password again'
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
				'type' => Element\Submit::class,
				'name' => 'create_account',
				'attributes' => [
					'value' => 'Create Account',
					'class' => 'btn btn-primary'
				]
			]);

	}

}



?>
