<?php
declare(strict_types=1);
namespace User;
// use Album\Controller\CourseController;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [  
	'router'=> [
		'routes' => [
			'signup' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/signup',
					'defaults' => [
						'controller' => Controller\AuthController::class,
						'action' => 'create',
					],
				],
			],
			'login' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/login',
					'defaults' => [
						'controller' => Controller\LoginController::class,
						'action' => 'index',
					],
				],
			],


			'logout' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/logout',
					'defaults' => [
						'controller' => Controller\LogoutController::class,
						'action' => 'index',
					],
				],
			],
			'profile' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/profile[/:id[/:username]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
	// Courses Route
			'course' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/course[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CourseController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            #Admin routes 
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],


            ],
            'enrollment' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/enrollment[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\EnrollmentController::class,
                        'action'     => 'index',
                    ],
                ],


            ],


			
		],
	],
	'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\LoginController::class => Controller\Factory\LoginControllerFactory::class,
            // Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class,
            Controller\EnrollmentController::class => Controller\Factory\EnrollmentControllerFactory::class,
            Controller\LogoutController::class => InvokableFactory::class,
            Controller\ProfileController::class => InvokableFactory::class,
            Controller\CourseController::class => Controller\Factory\CourseControllerFactory::class,
            Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,

        ],

    ],
	'view_manager'=> [
		'template_map'=> [
			'auth/create' => __DIR__ . '/../view/user/auth/create.phtml',
			'auth/login' => __DIR__ . '/../view/user/auth/index.phtml',
		 // 'profile/index' => __DIR__ . '/../view/user/profile/index.phtml',
		// 'admin/index' => __DIR__ . '/../view/user/admin/index.phtml',
		 // 'enrollment/index' => __DIR__ . '/../view/user/enrollment/index.phtml',
		],
		'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
	],

];


?>