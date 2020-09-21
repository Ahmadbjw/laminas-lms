<?php
declare(strict_types = 1);

namespace User\Model\Entity;

class UserEntity
{
	#we get columns from users table

protected $id;
protected $active;
protected $first_name;
protected $last_name;
protected $username;
protected $email;
protected $password;
protected $gender;
protected $created_at;
protected $updated_at;

public function getid()
{
	return $this->id; 
}

public function setid($id)
{
	$this->id = $id;
}

public function getFirstName()
{
	return $this->first_name; 
}

public function setFirstName($first_name)
{
	$this->first_name = $first_name;
}


public function getLastName()
{
	return $this->last_name; 
}

public function setLastName($last_name)
{
	$this->last_name = $last_name;
}



public function getUsername()
{
	return $this->username; 
}

public function setUsername($username)
{
	$this->username = $username;
}

public function getEmail()
{
	return $this->email; 
}

public function setEmail($email)
{
	$this->email=$email;
}

public function getPassword()
{
	return $this->password ; 
}

public function setPassword($password)
{
	$this->password = $password;
}

public function getGender()
{
	return $this->gender ; 
}

public function setGender($gender)
{
	$this->gender = $gender;
}

public function getCreatedAt()
{
	return $this->created_at; 
}

public function setCreatedAt($created_at)
{
	$this->created_at = $created_at;
}


public function getUpdatedAt()
{
	return $this->updated_at ; 
}

public function setUpdatedAt($updated_at)
{
	$this->updated_at = $updated_at;
}

public function getActive()
{
	return $this->active ; 
}

public function setActive($active)
{
	$this->active = $active;
}









}


?>