<?php
declare(strict_types =1);
namespace User\Controller\Factory;

// use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Controller\LoginController;
use User\Model\Table\EnrollmentTable;
use User\Model\Table\UserTable;

class LoginControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new LoginController(
			$container->get(Adapter::class),
			$container->get(UserTable::class),
			$container->get(EnrollmentTable::class)
		);
	}
}


?>