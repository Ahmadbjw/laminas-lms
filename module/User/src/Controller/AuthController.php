<?php

declare(strict_types=1);
namespace User\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use User\Form\Auth\CreateForm;
use User\Model\Table\UserTable;

class AuthController extends AbstractActionController{

	private $userTable;

	public function __construct(UserTable $userTable){
		$this->userTable = $userTable;
	}
	public function createAction()
	{
		$auth = new AuthenticationService();
		
		if($auth->hasIdentity())
		{
			#if user has session take them some else
			return $this->redirect()->toRoute('home');
		}
		
		$createForm = new CreateForm();
		$request = $this->getRequest();
		
		if($request->isPost())
		{
			$formData = $request->getPost()->toArray();
			$createForm->setInputFilter($this->userTable->getCreateFormFilter());
			$createForm->setData($formData);
			if($createForm->isValid())
			{
				try
				{
					$data = $createForm->getData();
					$this->userTable->saveAccount($data);
					$this->flashMessenger()->addSuccessMessage('Account Successfully created. You can now login');
					#we have not created login route yet. so we will redirect to the homepage
			        return $this->redirect()->toRoute('login');
				} 	catch(RuntimeException $exception)
				{
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();#refresh this page to view error

				}
			}
			else{
				print_r($createForm->getMessages());
				exit;
				}
		}
		return new ViewModel(['form' => $createForm]);
	}
}
?>