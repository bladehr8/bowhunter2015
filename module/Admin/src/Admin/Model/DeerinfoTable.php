<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class DeerinfoTable
{
    protected $tableGateway;
	public $infoArray = array();
	const JOIN_LEFT = 'left';

    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select(array('active'=> 1));
		 return $resultSet;
    }
	
	public function fetchRowsById($id)
    {
        $resultSet = $this->tableGateway->select(array('id'=> $id));
        return $resultSet;
    }
	
    public function fetchAllDear()
    {
		// $resultSet = $this->tableGateway->select();
        //return $resultSet;		
		 $adapter = $this->tableGateway->getAdapter();		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('deer_information');
		 $select->join('deer_activity', 'deer_information.deer_activity_id = deer_activity.id ' , array('activityName'=> 'name'),$type = self::JOIN_LEFT);
		 $select->join('deer_size', 'deer_information.deer_size_id = deer_size.id ' , array('sizeName'=> 'name'),$type = self::JOIN_LEFT);
		 $select->join('deer_facing', 'deer_information.deer_facing_id = deer_facing.id ' , array('facingName'=> 'name'),$type = self::JOIN_LEFT);
		 $select->join('deer_position', 'deer_information.deer_position_id = deer_position.id ' , array('positionName'=> 'name'),$type = self::JOIN_LEFT);
		 

		$select->where('deer_information.active = 1');		 
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();		
        return $rowset; 		
    }		
	
	
    public function fetchDataById($id = null)
    {
		// $resultSet = $this->tableGateway->select();
        //return $resultSet;		
		 $adapter = $this->tableGateway->getAdapter();		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('deer_information');
		 $select->join('deer_activity', 'deer_information.deer_activity_id = deer_activity.id ' , array('activityName'=> 'name'),$type = self::JOIN_LEFT);
		 $select->join('deer_size', 'deer_information.deer_size_id = deer_size.id ' , array('sizeName'=> 'name'),$type = self::JOIN_LEFT);
		 $select->join('deer_facing', 'deer_information.deer_facing_id = deer_facing.id ' , array('facingName'=> 'name'),$type = self::JOIN_LEFT);
		 $select->join('deer_position', 'deer_information.deer_position_id = deer_position.id ' , array('positionName'=> 'name'),$type = self::JOIN_LEFT);
		 
		 if(isset($id) && $id > 0)
		 {
			$id = (int) $id ; 
			$select->where('deer_information.id = '. $id );			
		 }	
		$select->where('deer_information.active = 1');		 
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();	
		$row = $rowset->current();	
        return $row; 		
    }		
		
	
	
	
	
	
	
	

    public function getDeerinfo($id)
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
	
	public function isExistBankName($bankName)
	{  
        $rowset = $this->tableGateway->select(array('name' => $bankName));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveDeerinfo(Deerinfo $deerinfo)
    {

	
        $data = array(
            'deer_activity_id' 	=> $deerinfo->deer_activity_id,
			'deer_size_id' 		=> $deerinfo->deer_size_id,
			'deer_facing_id' 	=> $deerinfo->deer_facing_id,
			'deer_position_id' 	=> $deerinfo->deer_position_id,
			'min_start_range' 	=> $deerinfo->min_start_range,
			'max_start_range' 	=> $deerinfo->max_start_range,
			'kill_for_success' 	=> $deerinfo->kill_for_success,	
			'active' 	=> $deerinfo->active
			
       );
	   
	  // if(!$this->isExistBankName($bank->id))
	  // {

			$id = (int)$deerinfo->id;
			if ($id == 0) {
				$data['created_on'] = $deerinfo->created_on ; 
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getDeerinfo($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   //}
    }
	
	public function updateBank(Bank $bank)
    {
        $data = array(
			'name' 		=> $bank->name,
			'quantity' 	=> $bank->quantity,
			'price' 	=> $bank->price,
			'active' 	=> $bank->active
			
       );

			$id = (int)$bank->id;
			
			if ($id == 0) {
				$this->tableGateway->insert($data);
				 $id = $this->tableGateway->getLastInsertValue(); //Add this line
			 } else {
				if ($this->getBank($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   return $id; // Add Return
    }

    public function deleteDeerinfo($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	# Load data from "deer_activity" - Table
	public function deerActivityLists($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('deer_activity');	
		
		if(isset($id) && $id > 0)
		{
			$id = (int) $id;
			$select->where(array('id' => $id ));
		}
		$select->where(array('active' => 1));
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
		
		foreach($rowset as $row){
			$this->infoArray[$row['id']] =  $row['name'];	
		}
		
		return $this->infoArray;

	}
	
	# Load data from "deer_size" - Table
	public function deerSizeLists($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('deer_size');	
		$select->where(array('active' => 1));
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
		foreach($rowset as $row){
			$this->infoArray[$row['id']] =  $row['name'];	
		}
		
		return $this->infoArray;
	}	
	
	
	# Load data from "deer_facing" - Table
	public function deerFacingLists($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('deer_facing');	
		$select->where(array('active' => 1));
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
		foreach($rowset as $row){
			$this->infoArray[$row['id']] =  $row['name'];	
		}
		
		return $this->infoArray;
	}		
	
	# Load Data From "deer_position" - Table
	public function deerPositionLists($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('deer_position');	
		$select->where(array('active' => 1));
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
		foreach($rowset as $row){
			$this->infoArray[$row['id']] =  $row['name'];	
		}
		
		return $this->infoArray;
	}
	
	
}