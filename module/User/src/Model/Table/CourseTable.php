<?php 
declare(strict_types=1);
namespace User\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\RowGateway\RowGateway;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\InputFilter\InputFilter;
use User\Model\Course;

class CourseTable extends AbstractTableGateway
{
	protected $tableGateway;
 	protected $table = 'courses';

	public function __construct(TableGatewayInterface $tableGateway)
    {
		$this->tableGateway = $tableGateway;
	}

	public function getAllCourses()
    {
		return $this->tableGateway->select();
	}

	public function getCourse($id)
    {
		$id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) 
        {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
	}

	public function saveCourse(Course $course)
    {
		$data = [
            'course' => $course->course,
        ];

        $id = (int) $course->id;

        if ($id === 0) 
        {
            $this->tableGateway->insert($data);
            return;
        }

        try 
        {
            $this->getCourse($id);
        } catch (RuntimeException $e) 
        {
            throw new RuntimeException(sprintf(
                'Cannot update course with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
	}

	public function deleteCourse($id)
    {
    	$this->tableGateway->delete(['id' => (int) $id]);
    }

}

