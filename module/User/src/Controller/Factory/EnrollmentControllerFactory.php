<?php

declare(strict_types=1);
namespace User\Controller\Factory;

// use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Controller\EnrollmentController;
use User\Model\Table\AdminTable;
use User\Model\Table\CourseTable;
use User\Model\Table\EnrollmentTable;
use User\Model\Table\UserTable;

class EnrollmentControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
		return new EnrollmentController(
			$container->get(EnrollmentTable::class),
			$container->get(CourseTable::class),
			$container->get(AdminTable::class),
			$container->get(UserTable::class)
		);
	}
}

?>