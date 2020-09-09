<?php
declare(strict_types=1);

namespace User\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\Auth\CreateForm;
use User\Model\Admin;
use User\Model\Table\AdminTable;



class AdminController extends AbstractActionController
{
    protected $adminTable;
    public function __construct(AdminTable $adminTable)
    {
    $this->adminTable = $adminTable;
  
    }

    public function checkAuth(){
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
    }

    public function indexAction()
    {  
        $this->checkAuth();
        
        return new ViewModel([
        	'users' => $this->adminTable->getAllUsers(),
        ]);
    }

    public function addAction ()
    {
        $this->checkAuth();
        
        $createForm = new CreateForm();

        $createForm->get('create_account')->setValue('Add user');
        $request = $this->getRequest();
        if(!$request->isPost())
        {
            return ['form' => $createForm];
        }

        $admin = new Admin();
        $createForm->setInputFilter($admin->getCreateFormFilter());
        $createForm->setData($request->getPost());
          
        if(! $createForm->isValid())
        {
        return ['form'=>$createForm];
        }
            
        $admin->exchangeArray($createForm->getData());
        $this->adminTable->saveAccount($admin);
        return $this->redirect()->toRoute('admin');
    }

    public function editAction()
    {
        $this->checkAuth();
        
        $userId = (int) $this->params()->fromRoute('id', 0);
        
        if (0 === $userId) 
        {
            return $this->redirect()->toRoute('admin', ['action' => 'add']);
        }
        
        try 
        {
            $editUser = $this->adminTable->getUser($userId);
        } 

        catch(\Exception $e)
        {
            return $this->redirect()->toRoute('admin', ['action' => 'index']);
        }
        
        $editForm = new CreateForm();
        $editForm->bind($editUser);

        $editForm->get('create_account')->setAttribute('value','Update User');
        $request  = $this->getRequest();
        
        $viewData = [
            'id'    => $userId,
            'form'  => $editForm
        ];

        if(! $request->isPost())
        {
            return $viewData;
        }

        $editForm->setInputFilter($editUser->getCreateFormFilter());
        $editForm->setData($request->getPost());

        if (! $editForm->isValid()) 
        {
            return $viewData;
        }

        $this->adminTable->saveAccount($editUser);
        return $this->redirect()->toRoute('admin', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $this->checkAuth();
        
        $userId = (int) $this->params()->fromRoute('id', 0);
        
        if (!$userId) 
        {
            return $this->redirect()->toRoute('admin');
        }
            $this->adminTable->deleteUser($userId);
            return $this->redirect()->toRoute('admin');

    }


}

?>