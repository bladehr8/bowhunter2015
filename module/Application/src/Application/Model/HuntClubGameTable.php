<?php
namespace Application\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
  

class HuntClubGameTable
{
    protected $tableGateway;
	public $arrayStack = array();
	
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
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
		//var_dump($row);
        return $row;
    }
	


    public function saveData(HuntClubGame $huntclubgame)
    {
        $data = array(
            'hunt_clubs_id' 			=> $huntclubgame->hunt_clubs_id,
			'light_conditions_id' 		=> $huntclubgame->light_conditions_id,
			'regions_id' 				=> $huntclubgame->regions_id,
			'number_of_animals' 		=> $huntclubgame->number_of_animals,
			'time_to_complete' 			=> $huntclubgame->time_to_complete,			
			'active' 					=> $huntclubgame->active
       );
	   


			$id = (int)$huntclubgame->id;
			if ($id == 0) {
			
				$data['created_on']  = $huntclubgame->created_on ; 				
				$this->tableGateway->insert($data);
				$lastId = $this->tableGateway->getLastInsertValue();
				return $lastId;
				
			 } else {
				if ($this->getData($id)) {					
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	  
    }
	


    public function deleteGame($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	
	# Method for drop-down population
	
	public function regionLists()
    {
		$this->arrayStack = array();
		$adapter 	= $this->tableGateway->getAdapter();		
		$sql       	= new Sql($adapter);
		$select    	= $sql->select();
		$select->from('region');
				
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		foreach($rowset as $row){
			$this->arrayStack[$row['id']] =  $row['name'];	
		}	
		//if(count($this->arrayStack) > 0 ) array_unshift($this->arrayStack, "Select Region");				
		return $this->arrayStack;		
    }	
	
	
	public function huntClubsLists()
    {
		$this->arrayStack = array();
		$adapter 	= $this->tableGateway->getAdapter();		
		$sql       	= new Sql($adapter);
		$select    	= $sql->select();
		$select->from('hunt_clubs');
				
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		foreach($rowset as $row){
			$this->arrayStack[$row['id']] =  $row['name'];	
		}
		//if(count($this->arrayStack) > 0 ) array_unshift($this->arrayStack, "Select Hunt Club");		
		return $this->arrayStack;		
    }
	
	
	public function lightConditionsLists()
    {
		$this->arrayStack = array();
		$adapter 	= $this->tableGateway->getAdapter();		
		$sql       	= new Sql($adapter);
		$select    	= $sql->select();
		$select->from('light_conditions');
				
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		foreach($rowset as $row){
			$this->arrayStack[$row['id']] =  $row['name'];	
		}	

		//if(count($this->arrayStack) > 0 ) array_unshift($this->arrayStack, "Select Light");
		return $this->arrayStack;		
    }
		
	
	
	
	
	
	
	
	
	
}