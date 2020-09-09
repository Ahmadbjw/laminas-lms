<?php

declare(strict_types=1);
namespace User;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use User\Model\Admin;
use User\Model\Course;
use User\Model\Enrollment;
use User\Model\Table\AdminTable;
use User\Model\Table\CourseTable;
use User\Model\Table\EnrollmentTable;
use User\Model\Table\UserTable;

class Module implements ConfigProviderInterface{

	public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getServiceConfig(): array
    {
		return [
			'factories'=> [
				UserTable::class => function($sm){
					$dbAdapter = $sm->get(Adapter::class);
					return new UserTable($dbAdapter);
				},

				CourseTable::class => function($container){
    				$tableGateway = $container->get(Model\CourseTableGateway::class);
    				return new CourseTable($tableGateway);
    			},

    			Model\CourseTableGateway::class => function($container){
    				$dbAdapter = $container->get(AdapterInterface::class);
    				$resultSetPrototype = new ResultSet();
    				$resultSetPrototype->setArrayObjectPrototype(new Course());
    				return new tableGateway('courses', $dbAdapter, null, $resultSetPrototype);
    			},

                #Admin configuration
                AdminTable::class => function($container){
                    $tableGateway = $container->get(Model\AdminTableGateway::class);
                    return new AdminTable($tableGateway);
                },

                Model\AdminTableGateway::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin());
                    return new tableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },

                #Enrollment configuration
                EnrollmentTable::class => function($container){
                    $gw1      = $container->get(Model\AdminTableGateway::class);
                    $gw2     = $container->get(Model\CourseTableGateway::class);
                    $gw3 = $container->get(Model\EnrollmentTableGateway::class);
                    return new EnrollmentTable($gw1, $gw2, $gw3);
                },

                Model\EnrollmentTableGateway::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Enrollment());
                    return new tableGateway('enrollments', $dbAdapter, null, $resultSetPrototype);
                },
			],
		];
	}
}

?>