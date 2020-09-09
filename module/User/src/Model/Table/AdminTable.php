<?php

namespace User\Model\Table;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\TableGatewayInterface;
use User\Model\Admin;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
// use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Filter;
use Laminas\I18n;
use Laminas\InputFilter;
use Laminas\Validator;
use User\Model\Entity\UserEntity;

class AdminTable extends AbstractTableGateway
{
    
	protected $tableGateway;
 	protected $table = 'users';

	public function __construct(TableGatewayInterface $tableGateway){
		$this->tableGateway = $tableGateway;
	}

    public function getAllUsers(){
    
        return $this->tableGateway->select();
    }


    public function getUser($id){
    	$userId = (int) $id;
    	$userRow = $this->tableGateway->Select(['id'=>$userId]);
    	$user = $userRow->current();
    	if(! $user){
    		throw new RuntimeException(sprintf(
    			'could not find the row with identifier %id',
    			$id
    		));
    		
    	}
    	return $user;
    }

    

    public function saveAccount(Admin $admin){
    	$data = [
			'first_name' 	=> $admin->first_name,
			'last_name' 	=> $admin->last_name,
			'email' 		=> $admin->email,
			'username' 		=> $admin->username,
			'gender' 		=> $admin->gender,
		    'password' 		=> (new Bcrypt())->create($admin->password)
		];

   $id = (int) $admin->user_id;


        if ($id === 0) {
            $this->tableGateway->insert($data);
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

        $this->tableGateway->update($data, ['id' => $id]);
    }


    public function deleteUser($id){
        $this->tableGateway->delete(['id' => (int) $id]);
    }

	
}


?>