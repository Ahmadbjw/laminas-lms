<?php

namespace User\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\I18n\Validator\Alnum;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\factory;
use Laminas\Validator\Csrf;
use Laminas\Validator\Db\NoRecordExists;
use Laminas\Validator\Db\RecordExists;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Identical;
use Laminas\Validator\InArray;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;




class Admin
{
    public $user_id;
    public $first_name;
    public $last_name;
    public $username;
    public $email;
    public $password;
    public $gender;
    public $created_at;
    

    public function exchangeArray(array $data)
    {
            $this->id           = !empty($data['id']) ? $data['id'] : null;
            $this->user_id      = !empty($data['id']) ? $data['id'] : null;
            $this->first_name   = !empty($data['first_name']) ? $data['first_name'] : null;
            $this->last_name    = !empty($data['last_name']) ? $data['last_name'] : null;
            $this->username     = !empty($data['username']) ? $data['username'] : null;
            $this->email        = !empty($data['email']) ? $data['email'] : null;
            $this->password     = !empty($data['password']) ? $data['password'] : null;
            $this->gender       = !empty($data['gender']) ? $data['gender'] : null;
            $this->created_at   = !empty($data['created_at']) ? $data['created_at'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'            => $this->user_id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'username'      => $this->username,
            'email'         => $this->email,
            'password'         => $this->password,
            'gender'        => $this->gender,
        ];
    }



    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
    public function getCreateFormFilter()
    {
        $inputFilter = new InputFilter();
        // $factory = new Factory();
        # filter and validate username input fields
        $inputFilter->add([
                    'name' =>'username',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],#stips html tags
                        ['name' => StringTrim::class], #remove empty spaces
                        // ['name' => Alnum::class],
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class], 
                        ['name' => Stringlength::class,
                        'options' =>[
                            'min' =>2,
                            'max' =>25,
                            // 'message' =>[
                            //     Stringlength::TOO_SHORT => 'Username must have at least 2 characters',
                            //     Stringlength::TOO_LONG => 'Username have atmost 25 characters',

                            // ]
                        ]
                    ],
                //     ['name' => Alnum::class,
                //     'options' => [
                //         'messages' => [
                //             Alnum::NOT_ALNUM =>  'Username must consist of alphanumeric characters only',
                //         ],
                //     ],
                // ],
                //
                // [
                //     'name' =>NoRecordExists::class,
                //     'options' => [
                //         // 'table' =>$this->table,
                //         'field' =>'username',
                //         // 'adapter' => $this->adapter,
                //             ],
                //         ],
                    ],
                
            ]
        );

        # filter and validate first_name input fields
        $inputFilter->add(
           
                [
                    'name' =>'first_name',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],#stips html tags
                        ['name' => StringTrim::class], #remove empty spaces
                        // ['name' => Alnum::class],
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class], 
                        ['name' => Stringlength::class,
                        'options' =>[
                            'min' =>2,
                            'max' =>25,
                            'message' =>[
                                Stringlength::TOO_SHORT => 'Username must have at least 2 characters',
                                Stringlength::TOO_LONG => 'Username must have atleast 25 characters',
                            ],
                        ],
                    ],
                    ],
                ],
        );

        # filter and validate last_name input fields
        $inputFilter->add(
                [
                    'name' =>'last_name',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],#stips html tags
                        ['name' => StringTrim::class], #remove empty spaces
                        // ['name' => Alnum::class],
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class], 
                        ['name' => Stringlength::class,
                        'options' =>[
                            'min' =>2,
                            'max' =>25,
                            // 'message' =>[
                            //     Stringlength::TOO_SHORT => 'Username must have at least 2 characters',
                            //     Stringlength::TOO_LONG => 'Username must have atleast 25 characters',
                            // ],
                        ],
                    ],
                    ],
                ]
        );
        // #filter and validate gender
        $inputFilter->add(
                [
                    'name' =>'gender',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],#stips html tags
                        ['name' => StringTrim::class], #remove empty spaces
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class], 
                        ['name' => InArray::class,
                        'options' => [

                            'haystack' => ['F','M','O'],
                            
                            ],
                        ],
                    ],
                ]
            
        );

        #filter and validate email input field
        $inputFilter->add(
           
                [
                    'name' =>'email',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],#stips html tags
                        ['name' => StringTrim::class], #remove empty spaces
                        // ['name' => StringTolower::class],
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class], 
                        ['name' => EmailAddress::class],
                        [
                            'name' => StringLength::class,
                            'options' =>[
                                'min' => 6,
                                'max' => 128,
                                // 'messages'=> [
                                //     Stringlength::TOO_SHORT => 'Email address must have at least 6 characters',
                                //     Stringlength::TOO_LONG => 'Email address must hve at most 128 characters',

                                // ],
                            ],
                        ],
                       
                    ],
                ]
            
        );

# filter and validate confirm_email field
$inputFilter->add(
        
                [
                    'name' =>'confirm_email',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],#stips html tags
                        ['name' => StringTrim::class], #remove empty spaces
                        // ['name' => StringTolower::class],
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class], 
                        ['name' => EmailAddress::class],
                        [
                            'name' => StringLength::class,
                            'options' =>[
                                'min' => 6,
                                'max' => 128,
                                'messages'=> [
                                    Stringlength::TOO_SHORT => 'Email address must have at least 6 characters',
                                    Stringlength::TOO_LONG => 'Email address must hve at most 128 characters',

                                ],
                            ],
                        ],
                       
                        [
                            'name' => Identical::class, #compare the specific fields
                            'options' => [
                                'token' => 'email', #field to compare against
                                'message'=>[
                                    Identical::NOT_SAME => 'Email address do not match!',
                                ],
                            ],
                        ],
                    ],
                ]
            
        );
#Filter and validate Password input field
        $inputFilter->add(
                [
                    'name'=> 'password',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],
                        ['name' => StringTrim::class],
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class],
                        [
                            'name' => Stringlength::class,
                            'options' => [
                                'min' => 8,
                                'max' => 25,
                                'message' => [
                                    Stringlength::TOO_SHORT => 'Password must have atleast  at 8 characters',
                                    StringLength::TOO_LONG => 'Password must have at most 25 characters',
                                ],
                            ],
                        ],
                    ],

                ]
            
        );

#filter and validate confirm password field
        $inputFilter->add(
                [
                    'name'=> 'confirm_password',
                    'required' => true,
                    'filters' => [
                        ['name' => StripTags::class],
                        ['name' => StringTrim::class],
                    ],
                    'validators' => [
                        // ['name' => NotEmpty::class],
                        [
                            'name' => Stringlength::class,
                            'options' => [
                                'min' => 8,
                                'max' => 25,
                                'message' => [
                                    Stringlength::TOO_SHORT => 'Password must have atleast  at 8 characters',
                                    StringLength::TOO_LONG => 'Password must have at most 25 characters',
                                ],
                            ],
                        ],
                        [
                            'name' => Identical::class,
                            'options'=>[
                                'token' => 'password',
                                'messages' => [
                                    Identical::NOT_SAME => 'Password do not match',
                                ],
                            ],
                        ],
                    ],
                ]
            
        );

        #csrf field

        // $inputFilter->add(
      
        //         [
        //             'name'=> 'csrf',
        //             'required' => true,
        //             'filters' => [
        //                 ['name' => StripTags::class],
        //                 ['name' => StringTrim::class],
        //             ],
        //             'validators' => [
        //                 // ['name' => NotEmpty::class],
        //                 [
        //                     'name' => Csrf::class,
        //                     'options' => [
        //                         'messages' => [
        //                             Csrf::NOT_SAME => 'Oops Refill the form',
        //                         ],
        //                     ],  
        //                 ],
        //             ],
        //         ]
            
        // );
       $this->inputFilter = $inputFilter;
        return $this->inputFilter;

    }

    	
}