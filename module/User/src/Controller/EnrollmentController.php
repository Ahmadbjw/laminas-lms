<?php
declare(strict_types =1);
namespace User\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\Enrollment\EnrollmentForm;
use User\Model\Admin;
use User\Model\Course;
use User\Model\Enrollment;
use User\Model\Table\AdminTable;
use User\Model\Table\CourseTable;
use User\Model\Table\EnrollmentTable;

class EnrollmentController extends AbstractActionController
{
	protected $enrollmentTable;
	protected $courseTable;
	protected $adminTable;

    public function __construct(EnrollmentTable $enrollmentTable, CourseTable $courseTable, AdminTable $adminTable)
    {
    	$this->enrollmentTable  = $enrollmentTable;
    	$this->courseTable		= $courseTable;
    	$this->adminTable		= $adminTable;
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
			'enrollments' => $this->enrollmentTable->getEnrollments()
        ]);
    }

    public function addAction()
    {
        $this->checkAuth();
        
    	$coursesArray  = [];
    	$learnersArray = [];

		$courses  	   = $this->courseTable->getAllCourses();
    	$learners 	   = $this->adminTable->getAllUsers();
		foreach($courses as $key => $course)
		{
    		$coursesArray[$course->id] = $course->course;
    	}

    	foreach($learners as $learner){
    		$learnersArray[$learner->id] = $learner->first_name;
    	}

        $enrollmentForm = new EnrollmentForm($coursesArray, $learnersArray);
        $enrollmentForm->get('enroll')->setValue('Enroll');
        $request 	= $this->getRequest();
		
		if(!$request->isPost())
		{
        	return ['form' => $enrollmentForm];
        }

		$enrollment = new Enrollment();
        $enrollmentForm->setInputFilter($enrollment->getInputFilter());
        $enrollmentForm->setData($request->getPost());

		if(! $enrollmentForm->isValid())
		{
        	return ['form'=>$enrollmentForm];
		}

        $enrollment->exchangeArray($enrollmentForm->getData());
	    $this->enrollmentTable->enroll($enrollment);
        return $this->redirect()->toRoute('enrollment');
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
            $editUser = $this->enrollmentForm->getUser($userId);
        } 

        catch(\Exception $e){
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

        if (! $editForm->isValid()) {
            return $viewData;
        }

        $this->enrollmentForm->saveAccount($editUser);
        return $this->redirect()->toRoute('admin', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $this->checkAuth();
        
        $enrollmentId = (int) $this->params()->fromRoute('id', 0);
        if (!$enrollmentId) 
        {
            return $this->redirect()->toRoute('enrollment');
        }

		$this->enrollmentTable->unenroll($enrollmentId);
        return $this->redirect()->toRoute('enrollment');
    }
}


?>