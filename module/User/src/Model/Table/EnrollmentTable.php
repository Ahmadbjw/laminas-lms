<?php

namespace User\Model\Table;

use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Filter;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\I18n;
use Laminas\InputFilter;
use Laminas\Validator;
use User\Model\Admin;
use User\Model\Enrollment;
use User\Model\Entity\UserEntity;
use User\Model\Table\AdminTable;
use User\Model\Table\CourseTable;

class EnrollmentTable extends AbstractTableGateway
{   
 	protected $table                    = "enrollments";
	protected $adminTableGateway        = "";
    protected $courseTableGateway       = "";
    protected $enrollmentTableGateway   = "";
    protected $adminTable               = "";
    protected $courseTable              = "";

	public function __construct(TableGatewayInterface $adminTableGateway, TableGatewayInterface $courseTableGateway, TableGatewayInterface $enrollmentTableGateway)
    {
        $this->enrollmentTableGateway      = $enrollmentTableGateway;

		$this->adminTableGateway = $adminTableGateway;
        $this->adminTable = new AdminTable($this->adminTableGateway);

        $this->courseTableGateway = $courseTableGateway;
        $this->courseTable = new CourseTable($this->courseTableGateway);
	}

    public function getEnrollments()
    {
        $records     = $this->enrollmentTableGateway->select();
        $enrollments = [];

        foreach($records as $record)
        {

            $learner                = $this->adminTable->getUser($record->user_id);
            $record->learner_name   = ($learner) ? $learner->first_name : "";

            $course                 = $this->courseTable->getCourse($record->course_id);
            $record->course_name    = ($course) ? $course->course : "";

            array_push($enrollments, $record);
        }

        return (object) $enrollments;

    }

    public function enroll(Enrollment $enrollment)
    {
    	$data = [
			'user_id' 	=> $enrollment->user_id,
			'course_id' => $enrollment->course_id
		];

        $id = (int) $enrollment->id;

        if ($id === 0) {
            $this->enrollmentTableGateway->insert($data);
            return;
        }

        try {
            $this->getUser($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }

        $this->enrollmentTableGateway->update($data, ['id' => $id]);
    }


    public function unenroll($id)
    {
        $this->enrollmentTableGateway->delete(['id' => (int) $id]);
    }
}

?>