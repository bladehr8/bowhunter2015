<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class DeerActivityTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
	
	public function fetchRowsById($id)
    {
        $resultSet = $this->tableGateway->select(array('id'=> $id));
        return $resultSet;
    }
	

    public function getData($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        //if (!$row) {
            //throw new \Exception("Could not find row $id");
        //}
		//var_dump($row);
        return $row;
    }
	
	public function isExistName($name)
	{  		
		$valid = true;
		$name 	= trim($name);		
		$adapter	= $this->tableGateway->getAdapter();
		

		 if (null === $adapter) {
			throw new \Exception('No database adapter present');
		}
		
		$validator = new RecordExists(
						array(
						'table'   => 'deer_activity',
						'field'   => 'name',
						'adapter' => $adapter
						)

		);

		// We still need to set our database adapter
		$validator->setAdapter($adapter);
		// Validation is then performed as usual
		if ($validator->isValid($name)) {
					$valid = true;
		} else {	
					$valid = false;			
		}		
		return $valid;
    }

    public function saveData(DeerActivity $deeractivity)
    {
        $data = array(
            'name' => $deeractivity->name,
			'active' => $deeractivity->active
       );

			$id = (int)$deeractivity->id;
			if ($id == 0) {
				$data['created_on'] = $deeractivity->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   
    }


    public function deleteData($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}