<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class DeerPositionTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
       # use a closure to manipulate the Select object 
	   $active = 1;
	   $resultSet = $this->tableGateway->select(
		function (Select $select) use ($active) {
        //$select->columns(array('id', 'name', 'type')); 
        $select->order('name ASC'); 
        //$select->where(array('active'=> $active));
    });
	
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
       // $rowset = $this->tableGateway->select(array('name' => $name));
        //$row = $rowset->current();
       // if (!$row) {
		//return false;
            //throw new \Exception("Could not find row $id");
        //}
        //return true;
		
		$valid = true;
		$name 	= trim($name);		
		$adapter	= $this->tableGateway->getAdapter();
		//$adapter = null;

		 if (null === $adapter) {
			throw new \Exception('No database adapter present');
		}

		
		$validator = new RecordExists(
						array(
						'table'   => 'deer_position',
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
		
		// username is invalid; print the reason
		//$//messages = $validator->getMessages();
		//foreach ($messages as $message) {
		//echo "$message\n";
		//}

    }

    public function saveData(DeerPosition $deerPosition)
    {
        $data = array(
            'name' => $deerPosition->name,			
			'active' => $deerPosition->active
       );

			$id = (int)$deerPosition->id;
			if ($id == 0) {
				$data['created_on'] = $deerPosition->created_on ;
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