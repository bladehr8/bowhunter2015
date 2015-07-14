<?php
namespace Admin\Model;
use Zend\Db\TableGateway\TableGateway;


use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 








  

class HunttypeTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';

    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        //$resultSet = $this->tableGateway->select();
        //return $resultSet;
		
		$adapter = $this->tableGateway->getAdapter();

		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('hunt_type');
		$select->join('hunt_class', 'hunt_type.hunt_class_id = hunt_class.id ' , array('className'=> 'name' , 'short_key'),$type = self::JOIN_LEFT);
		//$select->where('hunt_type.active = 1');		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();

		//$resultSet = new ResultSet();
		//$resultSet->initialize($rowset);
		//return $resultSet;


		return $rowset; 

    }
	
	public function fetchRowsById($id)
    {
        //$resultSet = $this->tableGateway->select(array('id'=> $id));
        //return $resultSet;
		$adapter = $this->tableGateway->getAdapter();

		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('hunt_type');
		$select->join('hunt_class', 'hunt_type.hunt_class_id = hunt_class.id ' , array('className'=> 'name' , 'short_key'),$type = self::JOIN_LEFT);
		if( $id > 0 ) $select->where('hunt_type.id = '. $id);		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();

		//$resultSet = new ResultSet();
		//$resultSet->initialize($rowset);
		//return $resultSet;

		$row = $rowset->current();
		return $row; 
	
    }
	

    public function getHunttype($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
		//var_dump($row);
        return $row;
    }
	
	public function isExistName($name)
	{  
        $rowset = $this->tableGateway->select(array('name' => $name));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveData(Hunttype $hunttype)
    {
        $data = array(
            'name' 				=> $hunttype->name,
			'hunt_class_id'		=> $hunttype->hunt_class_id,
			'active' 			=> $hunttype->active			
       );
	   
		  // if(!$this->isExistName($hunttype->name))
		 //  {
					$id = (int)$hunttype->id;
					if ($id == 0) {
						$this->tableGateway->insert($data);
					 } else {
						if ($this->getHunttype($id)) {
							$this->tableGateway->update($data, array('id' => $id));
						} else {
							throw new \Exception('Id does not exist');
						}
					}
		   //}
    }
	

    public function deleteData($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}