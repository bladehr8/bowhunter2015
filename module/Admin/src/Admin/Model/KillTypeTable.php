<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class KillTypeTable
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
	

    public function getKillType($id)
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
	
	public function isExistName($killType)
	{  
       // $rowset = $this->tableGateway->select(array('name' => $name));
        //$row = $rowset->current();
       // if (!$row) {
		//return false;
            //throw new \Exception("Could not find row $id");
        //}
        //return true;
		
		$valid = true;
		$killType 	= trim($killType);		
		$adapter	= $this->tableGateway->getAdapter();
		//$adapter = null;

		 if (null === $adapter) {
			throw new \Exception('No database adapter present');
		}

		
		$validator = new RecordExists(
						array(
						'table'   => 'kill_type',
						'field'   => 'name',
						'adapter' => $adapter
						)

		);

		// We still need to set our database adapter
		$validator->setAdapter($adapter);
		// Validation is then performed as usual
		if ($validator->isValid($killType)) {
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

    public function saveKillType(KillType $killtype)
    {
        $data = array(
            'name' => $killtype->name,
			'active' => $killtype->active
       );
	   
	   //if(!$this->isExistRegionName($region->id))
	  // {

			$id = (int)$killtype->id;
			if ($id == 0) {
				$data['created_on'] = $killtype->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getKillType($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   //}
    }


    public function deleteKillType($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}