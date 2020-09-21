<?php
declare(strict_types=1);
namespace User\Model\Table;

// use Laminas\Crypt\Password\Bcrypt;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Filter;
use Laminas\I18n;
use Laminas\InputFilter;
use Laminas\Validator;
use User\Model\Entity\UserEntity;
class UserTable extends AbstractTableGateway {
	protected $adapter;
	protected $table = 'users';

	public function __construct(Adapter $adapter){
		$this->adapter = $adapter;
		$this->initialize();
	}

	public function fetchAccountByEmail(string $email)
	{
		$sqlQuery = $this->sql->select()->where(['email' => $email]);
		$sqlstmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlstmt->execute()->current();
		// print_r($handler);
		// exit();
		if(!$handler)
		{
			return null;
		}



		$classMethod = new ClassMethodsHydrator();

		$entity		= new UserEntity(); 
		$classMethod->hydrate($handler, $entity);
		// print_r($entity);
		// exit();
		return $entity;
 	}


	#sanitzes our login form
	public function getLoginFormFilter()
	{
		$inputFilter 	= new InputFilter\InputFilter();
		$factory 		= new InputFilter\Factory();

		#filter and validate Email
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'email',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],
						['name' => Filter\StringTrim::class],
						['name' => Filter\StringTolower::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 6,
								'max' => 128,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'Email must have atleast 6 characters!',
									Validator\StringLength::TOO_LONG => 'Email must have atleast atmost 128 characters!',
								],
							],
						],
						['name' => Validator\EmailAddress::class],
						[
							'name' => Validator\Db\RecordExists::class,
							'options' => [
								'table' => $this->table,
								'field' => 'email',
								'adapter' => $this->adapter,
							],
						],
					],
				]
			)
		);

		#fileter and vaildate Password
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],
						['name' => Filter\StringTrim::class]
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'message' => [
									Validator\StringLength::TOO_SHORT => 'Password must have atleast 8 character!',
									Validator\StringLength::TOO_LONG => 'Password must have atmost 25 characher',
								],
							],
						],
					],
				]
			)
		);

		#recall checkbox
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'recall',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],
						['name' => Filter\StringTrim::class],
						['name' => Filter\ToInt::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						['name' =>I18n\Validator\IsInt::class],
						[
							'name' => Validator\InArray::class,
							'options' => [
								'haystack' => [0,1]
							],
						],
					],
				]
			)
		);

		#csrf
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'csrf',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],
						['name' => Filter\StringTrim::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Csrf::class,
							'options' => [
								'messages' => [
									Validator\Csrf::NOT_SAME => 'Oops! Refill the form and try again',
								],
							],
						],
					],
				]
			)
		);

		return $inputFilter;
	}
	#sanitizes our registration form 
	public function getCreateFormFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();
		# filter and validate username input fields
		$inputFilter->add(
			$factory->createInput(
				[
					'name' =>'username',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],#stips html tags
						['name' => Filter\StringTrim::class], #remove empty spaces
						['name' => I18n\Filter\Alnum::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						['name' => Validator\Stringlength::class,
						'options' =>[
							'min' =>2,
							'max' =>25,
							'message' =>[
								Validator\Stringlength::TOO_SHORT => 'Username must have at least 2 characters',
								Validator\Stringlength::TOO_SHORT => 'Username must have atleast 25 characters',

							]
						]
					],
					['name' => I18n\Validator\Alnum::class,
					'options' => [
						'messages' => [
							I18n\Validator\Alnum::NOT_ALNUM =>	'Username must consist of alphanumeric characters only',
						],
					],
				],
				[
					'name' =>Validator\Db\NoRecordExists::class,
					'options' => [
						'table' =>$this->table,
						'field' =>'username',
						'adapter' => $this->adapter,
							],
						],
					],
				]
			)
		);

		# filter and validate first_name input fields
		$inputFilter->add(
			$factory->createInput(
				[
					'name' =>'first_name',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],#stips html tags
						['name' => Filter\StringTrim::class], #remove empty spaces
						['name' => I18n\Filter\Alnum::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						['name' => Validator\Stringlength::class,
						'options' =>[
							'min' =>2,
							'max' =>25,
							'message' =>[
								Validator\Stringlength::TOO_SHORT => 'Username must have at least 2 characters',
								Validator\Stringlength::TOO_SHORT => 'Username must have atleast 25 characters',
							],
						],
					],
					],
				]),
		);

		# filter and validate last_name input fields
		$inputFilter->add(
			$factory->createInput(
				[
					'name' =>'last_name',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],#stips html tags
						['name' => Filter\StringTrim::class], #remove empty spaces
						['name' => I18n\Filter\Alnum::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						['name' => Validator\Stringlength::class,
						'options' =>[
							'min' =>2,
							'max' =>25,
							'message' =>[
								Validator\Stringlength::TOO_SHORT => 'Username must have at least 2 characters',
								Validator\Stringlength::TOO_SHORT => 'Username must have atleast 25 characters',
							],
						],
					],
					],
				])
		);
		// #filter and validate gender
		$inputFilter->add(
			$factory->createInput(
				[
					'name' =>'gender',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],#stips html tags
						['name' => Filter\StringTrim::class], #remove empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						['name' => Validator\InArray::class,
						'options' => [

							'haystack' => ['F','M','O'],
							
							],
						],
					],
				]
			)
		);

		#filter and validate email input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' =>'email',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],#stips html tags
						['name' => Filter\StringTrim::class], #remove empty spaces
						['name' => Filter\StringTolower::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						['name' => Validator\EmailAddress::class],
						[
							'name' => Validator\StringLength::class,
							'options' =>[
								'min' => 6,
								'max' => 128,
								'messages'=> [
									Validator\Stringlength::TOO_SHORT => 'Email address must have at least 6 characters',
									Validator\Stringlength::TOO_LONG => 'Email address must hve at most 128 characters',

								],
							],
						],
						[
							'name' => Validator\Db\NoRecordExists::class,
							'options' => [
								'table' => $this->table,
								'field' => 'email',
								'adapter' => $this->adapter,

							],
						],
					],
				]
			)
		);

# filter and validate confirm_email field
$inputFilter->add(
			$factory->createInput(
				[
					'name' =>'confirm_email',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],#stips html tags
						['name' => Filter\StringTrim::class], #remove empty spaces
						['name' => Filter\StringTolower::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						['name' => Validator\EmailAddress::class],
						[
							'name' => Validator\StringLength::class,
							'options' =>[
								'min' => 6,
								'max' => 128,
								'messages'=> [
									Validator\Stringlength::TOO_SHORT => 'Email address must have at least 6 characters',
									Validator\Stringlength::TOO_LONG => 'Email address must hve at most 128 characters',
								],
							],
						],
						[
							'name' => Validator\Db\NoRecordExists::class,
							'options' => [
								'table' => $this->table,
								'field' => 'email',
								'adapter' => $this->adapter,
							],
						],
						[
							'name' => Validator\Identical::class, #compare the specific fields
							'options' => [
								'token' => 'email', #field to compare against
								'message'=>[
									Validator\Identical::NOT_SAME => 'Email address do not match!',
								],
							],
						],
					],
				]
			)
		);
#Filter and validate Password input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name'=> 'password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],
						['name' => Filter\StringTrim::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Stringlength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'message' => [
									Validator\Stringlength::TOO_SHORT => 'Password must have atleast  at 8 characters',
									Validator\StringLength::TOO_LONG => 'Password must have at most 25 characters',
								],
							],
						],
					],

				]
			)
		);

#filter and validate confirm password field
		$inputFilter->add(
			$factory->createInput(
				[
					'name'=> 'confirm_password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],
						['name' => Filter\StringTrim::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Stringlength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'message' => [
									Validator\Stringlength::TOO_SHORT => 'Password must have atleast  at 8 characters',
									Validator\StringLength::TOO_LONG => 'Password must have at most 25 characters',
								],
							],
						],
						[
							'name' => Validator\Identical::class,
							'options'=>[
								'token' => 'password',
								'messages' => [
									Validator\Identical::NOT_SAME => 'Password do not match',
								],
							],
						],
					],
				]
			)
		);

		// #csrf field

		// $inputFilter->add(
		// 	$factory->createInput(
		// 		[
		// 			'name'=> 'csrf',
		// 			'required' => true,
		// 			'filters' => [
		// 				['name' => Filter\StripTags::class],
		// 				['name' => Filter\StringTrim::class],
		// 			],
		// 			'validators' => [
		// 				['name' => Validator\NotEmpty::class],
		// 				[
		// 					'name' => Validator\Csrf::class,
		// 					'options' => [
		// 						'messages' => [
		// 							Validator\Csrf::NOT_SAME => 'Oops Refill the form',
		// 						],
		// 					],	
		// 				],
		// 			],
		// 		]
		// 	)
		// );
		return $inputFilter;

	}

	public function saveAccount(array $data)
	{
		$values = [
			'first_name' => ucfirst($data['first_name']),
			'last_name'  => ucfirst($data['last_name']),
			'username' 	 => ($data['username']),
			'gender' 	 => ($data['gender']),
			'email' 	 => mb_strtolower($data['email']),
			'password' 	 => (new bcrypt())->create($data['password']),
		];

		$sqlQuery = $this->sql->insert()->values($values);

		$sqlstmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		return $sqlstmt->execute();
	}
}
?>