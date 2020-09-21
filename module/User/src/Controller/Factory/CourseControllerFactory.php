<?php
declare(strict_types=1);
namespace User\Controller\Factory;

// use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Controller\CourseController;
use User\Model\Table\AdminTable;
use User\Model\Table\CourseTable;

class CourseControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
		return new CourseController(
			$container->get(CourseTable::class),
			$container->get(AdminTable::class)
		);
	}
}

?>