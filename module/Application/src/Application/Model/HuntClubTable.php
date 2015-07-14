<?php
namespace Application\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
  

class HuntClubTable
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
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
		//var_dump($row);
        return $row;
    }
	public function saveHuntClubsHasPlayers($data)
	{
		$adapter = $this->tableGateway->getAdapter();
		$sql       = new Sql($adapter);
		$insert    = $sql->insert('hunt_clubs_has_players');	
		$insert->values($data);				
		$statement = $sql->prepareStatementForSqlObject($insert);				
		$results    = $statement->execute();			
		$id = $adapter->getDriver()->getLastGeneratedValue();				
		return $id;	
	}
	
	
	
	
	
	
	
	


    public function saveData(HuntClub $huntclub)
    {
        $data = array(
            'player_id' 	=> $huntclub->player_id,
			'name' 			=> $huntclub->name,		
			'active' 		=> $huntclub->active
       );
	   


			$id = (int)$huntclub->id;
			if ($id == 0) {
			
				$data['created_on']  = $huntclub->created_on ; 
				$data['modified_on'] = $huntclub->modified_on ; 
				$this->tableGateway->insert($data);
				$lastId = $this->tableGateway->getLastInsertValue();
				$huntClubsHasPlayers = array('hunt_clubs_id' =>$lastId , 'players_id' => $huntclub->player_id );
				$this->saveHuntClubsHasPlayers($huntClubsHasPlayers);			
				return $lastId;
				
			 } else {
				if ($this->getData($id)) {
					$data['modified_on'] = $huntclub->modified_on ; 
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	  
    }
	


    public function deleteClub($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}