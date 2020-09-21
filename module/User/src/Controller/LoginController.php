<?php
declare(strict_types =1);
namespace User\Controller;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\SessionManager;
use Laminas\View\Model\ViewModel;
use User\Form\Auth\LoginForm;
use User\Model\Entity\userEntity;
use User\Model\Table\EnrollmentTable;
use User\Model\Table\UserTable;

class LoginController extends AbstractActionController
{
	private $adapter; #database adapter
	private $usersTable; #table we store
	private $enrollmentTable;
	public function __construct(Adapter $adapter,UserTable $usersTable,EnrollmentTable $enrollmentTable)
		{
			$this->adapter 			= $adapter;
			$this->usersTable 		= $usersTable;
			$this->enrollmentTable 	= $enrollmentTable;
		}
	public function indexAction()
	{
		$auth = new AuthenticationService();
		if($auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}

		
		$loginForm = new LoginForm();
		//Laminas look the view in view/login/index.phtml
		//but we want to look in auth directory rather then Login directory 
		//so how we can tell Laminas to do that work 


		$request = $this->getRequest();
		if($request->isPost()){
			$formData = $request->getPost()->toArray();
			$loginForm->setInputFilter($this->usersTable->getLoginFormFilter());
			$loginForm->setData($formData);			

			 $info = $this->usersTable->fetchAccountByEmail($formData['email']);


			if($loginForm->isValid()){
				$authAdapter = new CallbackCheckAdapter($this->adapter);
				// $hash 		 = password_hash($formData['password'], PASSWORD_DEFAULT);
				$hash 		 = $info->getPassword();
				$id = $info->getid();
				$active = $info->getActive();
				// $this->enrollmentTable->setUserId($info);
				// $this->enrollmentTable->getEnrollments($id);
				// $this->enrollmentTable->setActive($active);
				// print_r($id);
				// exit();
				
			
				$password 	 = $formData['password'];
				$passwordValidation = function ($hash, $password) {
				    return password_verify($password, $hash);
				};

				$authAdapter
					->setTableName($this->usersTable->getTable())
					->setIdentityColumn('email')
					->setCredentialColumn('password')
					->setCredentialValidationCallback($passwordValidation);

				$authAdapter
			    ->setIdentity($formData['email'])
			    ->setCredential($formData['password']);

			    $result = $authAdapter->authenticate();


				switch($result->getCode()){
					case Result::FAILURE_IDENTITY_NOT_FOUND :
					$this->flastMessenger()->addErrorMessage('Unknown Email Address');
					return $this->redirect()->refresh();
					break;

					case Result::FAILURE_CREDENTIAL_INVALID:
					$this->flashMessenger()->addErrorMessage('Incorrect Password');
					return $this->redirect()->refresh();
					break;

					case RESULT::SUCCESS:
					if($data['recall'] == 1){
						$session_manager = new SessionManager();
						$timeForSession = 1814400;
						$session_manager->rememberMe($timeForSession);
					}
					$storage = $auth->getStorage();
					$storage->write($authAdapter->getResultRowObject('created_at','updated_at'));
					$id = 	$info->getid();
					$username = $info->getUsername();
 					return $this->redirect()->toRoute(
						'enrollment',
						[
							'id' 		=> 	$info->getid(),
							'username' 	=> 	mb_strtolower($info->getUsername())
						]
					);
					break;

					default:
					$this->flashMessenger()->addErrorMessage('Authentication Failed. Try again');
					return $this->redirect()->refresh();
					break;
				}
			} 
			else {
				$loginForm->getMessages();
				echo 'form is not valid';
				exit;
			}
		} 

		return (new ViewModel(['form' => $loginForm]))->setTemplate('user/auth/login');
	}
}


?>