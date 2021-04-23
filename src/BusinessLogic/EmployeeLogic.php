<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Employee;
use Romi\Shared\Cryptography;
use Romi\Domain\Tenant;

class EmployeeLogic extends BaseLogic {

	
    public function loadEmployee()
	{
		
		$employee = $this->getEntityManager()->getRepository(Employee::class)->findAll();
		return $employee;
		
	}

	// save and update
	public function createEmployee($params)
	{
		//Find profile if exist
		$existEmployee = $this->getEntityManager()
			->getRepository(Employee::class)
			->findOneBy(array('idCardNo' => $params['idCardNo']));

		//If not exist, create new employee
		if (!$existEmployee) {
			$employee = $this->createEmployeeEntity($params);
			$this->saveOrUpdate($employee);
			return true;
		} else {
			return $existEmployee;
		}
	}

	protected function createEmployeeEntity($params)
	{
		$employee = new Employee();
		$this->setEmployeeEntity($params, $employee);
		return $employee;
	}

	public function setEmployeeEntity($params, Employee &$employee)
	{
	
		$dayToWork = new \DateTime($params['dayToWork']);
		$createdAt = new \DateTime($params['createdAt']);
        $updatedAt = new \DateTime($params['updatedAt']);

        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $params['idTenant']));

		$employee->setTenantId($tenantId);
		$employee->setName($params['name']);
		$employee->setIdCardNo($params['idCardNo']);
		$employee->setGender($params['gender']);
		$employee->setPhoneNumber($params['phoneNumber']);
		$employee->setAddress($params['address']);
		$employee->setPosition($params['position']);
		$employee->setShift($params['shift']);
		$employee->setCode($params['code']);
		$employee->setDayToWork($dayToWork);
		$employee->setYearOfBirth($params['yearOfBirth']);

		$employee->setCreatedBy($params['createdBy']);
        $employee->setUpdatedBy($params['updatedBy']);
        $employee->setCreatedAt($createdAt); 
        $employee->setUpdatedAt($updatedAt);

	
	
	}


	
	public function deleteEmployee($employeeId)
	{
		$employee = $this->getEntityManager()
			->getRepository(Employee::class)
			->findOneBy(array('id' => $employeeId));
		if ($employee) {
			$this->delete($employee);
			return true;
		}
		return false;
	}
	

	public function loadEmployeeById($id)
	{
		$emp = $this->getEntityManager()
			->getRepository(Employee::class)
			->findOneBy(array('id' => $id));
		return $emp;
	}
	
	public function updateEmployee($params)
	{
		$employee = $this->loadEmployeeById($params['id']);
		if (!$employee) {
			return false;
		}
		$this->setEmployeeEntity($params, $employee);
		$this->getEntityManager()->flush();
		return true;
	}	


}
