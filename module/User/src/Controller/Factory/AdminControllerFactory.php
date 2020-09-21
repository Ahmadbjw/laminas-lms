<?php
declare(strict_types=1);
namespace User\Controller\Factory;

// use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Controller\AdminController;
use User\Model\Table\AdminTable;

class AdminControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
		return new AdminController(
			$container->get(AdminTable::class)
		);
	}
}

?>