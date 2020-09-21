<?php 

declare(strict_types=1);
namespace User\Controller;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\Courses\CourseForm;
use User\Model\Course;
use User\Model\Table\AdminTable;
use User\Model\Table\CourseTable;
class CourseController extends AbstractActionController
{
	private $courseTable;
    private $adminTable;
	public function __construct(CourseTable $courseTable, AdminTable $adminTable)
	{

	    $this->courseTable = $courseTable;
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
            'courses' => $this->courseTable->getAllCourses(),
            'users'   => $this->adminTable->getAllUsers(), 
	     ]);
    }

	public function editAction()
	{
        $this->checkAuth();
		$id = (int) $this->params()->fromRoute('id', 0);
        if (0 === $id) 
        {
            return $this->redirect()->toRoute('course', ['action' => 'add']);
        }

        try 
        {
            $editcourse = $this->courseTable->getCourse($id);
        }
         catch(\Exception $e)
        {
        	return $this->redirect()->toRoute('course', ['action' => 'index']);
        }

        $editForm = new CourseForm();
        $editForm->bind($editcourse);

        $editForm->get('submit')->setAttribute('value','Update');
        $request  = $this->getRequest();
        $viewData = [
        	'id' 	=> $id,
        	'form'	=> $editForm
        ];

        if(! $request->isPost())
        {
        	return $viewData;
        }

        $editForm->setInputFilter($editcourse->getInputFilter());
        $editForm->setData($request->getPost());

        if (! $editForm->isValid()) {
            return $viewData;
        }

        $this->courseTable->saveCourse($editcourse);
        return $this->redirect()->toRoute('course', ['action' => 'index']);
        	
	}

	public function addAction()
    {
        $this->checkAuth();
        $form = new CourseForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $course = new Course();
        $form->setInputFilter($course->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $course->exchangeArray($form->getData());
        $this->courseTable->saveCourse($course);
        return $this->redirect()->toRoute('course');
    }

	public function deleteAction()
	{
        $this->checkAuth();
		$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('course');
        }
                $this->courseTable->deleteCourse($id);
            return $this->redirect()->toRoute('course');

    }

}


