<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class TournamentTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }
	
	#  List of Method For Web Service
	# Get tournament details / BY ID
	public function getTournamentLists($id = null)
	{
		$adapter	= $this->tableGateway->getAdapter();		
		$sql		= new Sql($adapter);
		$select		= $sql->select();
		$select->from('tournament_has_missions');
		$select->join('tournament', 'tournament_has_missions.tournament_id = tournament.id ' , array('tournamentName'=>'name' ,'duration' ,'start_date' , 'end_date' , 'tournament_type' ,'created_on' , 'active', 'entry_points'),$type = self::JOIN_LEFT);		
		$select->join('missions', 'tournament_has_missions.missions_id = missions.id ' , array('missionName' => 'name' ,'minimum_points_required' , 'duration' , 'difficulty_level' , 'no_more_hunts' , 'kill_for_success' , 'premium_currency_rewarded' ,'normal_currency_rewarded' , 'xp_rewarded' ,'created_on' , 'active'),$type = self::JOIN_LEFT);		
		

		
		//$select->join('hunt_suggestion', 'missions.hunt_suggestion_id = hunt_suggestion.id ' , array('suggestionName'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('shorthand_target_type', 'missions.shorthand_target_type_id = shorthand_target_type.id ' , array('targetName'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('hunt_type', 'missions.hunt_types_id = hunt_type.id ' , array('huntType'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('player_position', 'missions.player_position_id = player_position.id ' , array('position'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('region', 'missions.region_id = region.id ' , array('regionName'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('light_conditions', 'missions.light_conditions_id = light_conditions.id ' , array('lightCondition'=> 'name'),$type = self::JOIN_LEFT); 
		$select->join('kill_type', 'missions.kill_type_id = kill_type.id ' , array('killType'=> 'name'),$type = self::JOIN_LEFT);


		if(isset($id))
		{
			$select->where('tournament_has_missions.tournament_id = '.$id);
			$select->where('tournament_has_missions.active = 1');	
		} 

		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		return $rowset; 
	}	
	
	
	# List of Deer information related to Missions - missions_has_deer_information
	public function getMissionHasDeerInfo($id = null)
	{
		$adapter	= $this->tableGateway->getAdapter();		
		$sql		= new Sql($adapter);
		$select		= $sql->select();
		$select->from('missions_has_deer_information');		
		//$select->join('deer_information', 'missions_has_deer_information.deer_information_id = deer_information.id' , array('min_start_range' , 'max_start_range' ,'kill_for_success' , 'created_on' , 'active'),$type = self::JOIN_LEFT);
		//$select->join('deer_activity', 'deer_information.deer_activity_id = deer_activity.id ' , array('activityName'=> 'name'),$type = self::JOIN_LEFT);
		//$select->join('deer_size', 'deer_information.deer_size_id = deer_size.id ' , array('sizeName'=> 'name'),$type = self::JOIN_LEFT);
		//$select->join('deer_facing', 'deer_information.deer_facing_id = deer_facing.id ' , array('facingName'=> 'name'),$type = self::JOIN_LEFT);
		//$select->join('deer_position', 'deer_information.deer_position_id = deer_position.id ' , array('positionName'=> 'name'),$type = self::JOIN_LEFT);

		if(isset($id))
		{
			$select->where('missions_has_deer_information.mission_id = '.$id);
			
		} 

		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		return $rowset; 

	}
	
	
	public function getMissionsHasHuntSuggestions($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('missions_has_hunt_suggestions');	
		$select->join('hunt_suggestion', 'missions_has_hunt_suggestions.suggestion_id = hunt_suggestion.id ' , array('*'),$type = self::JOIN_LEFT);
		
		
		
		if(isset($id) && $id > 0)
		{
			$id = (int) $id;
			$select->where(array('missions_has_hunt_suggestions.mission_id' => $id ));
		}		
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();	

		foreach($rowset as $row) { $huntSuggestionData[] = $row;	 } 
		
		
		/*
		foreach($rowset as $row){
			$this->infoArray[$row['id']] =  $row['suggestion_id'];	
		}		
		return $this->infoArray;
		*/
		return $huntSuggestionData;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	# ++++++++++++++++++++  End Of Web Service Methdos +++++++++++++++++++++++++++++++++++++++++++++++++++
	
	
	
	
	
	

    public function fetchAll()
    {
		
		$adapter 		= $this->tableGateway->getAdapter();
		$sql       		= new Sql($adapter);
		$select    		= $sql->select();
		$select->from('tournament');				
		$statement 		= $sql->prepareStatementForSqlObject($select);				
		$rowset   		= $statement->execute();		
		return $rowset; 

    }
	
	public function loadByType( $type=null )
    {
		
		$adapter 		= $this->tableGateway->getAdapter();
		$sql       		= new Sql($adapter);
		$select    		= $sql->select();
		$select->from('tournament');
		if( $type != null ) $select->where(array('tournament_type' => $type )  );	
			
		$statement 		= $sql->prepareStatementForSqlObject($select);				
		$rowset   		= $statement->execute();		
		return $rowset; 

    }
	
	
	public function regionList()
    {
       // $resultSet = $this->tableGateway->select();
        //return $resultSet;
		
		$adapter = $this->tableGateway->getAdapter();
		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('region');
		/* $select->join('attributeset', 'tournament.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('tournament.setId = '.$setid1);
		 } */
				
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		//$row = $rowset->current();
		
		
			
        return $rowset; 
		
    }

    public function getTournament($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistTournamentName($tournamentname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $tournamentname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveTournament(Tournament $tournament)
    {
        $data = array(		
						'name' 				=> $tournament->name,
						'duration' 			=> $tournament->duration,								
						'start_date' 		=> $tournament->start_date,
						'end_date' 			=> $tournament->end_date,
						'tournament_type' 	=> $tournament->tournament_type,
						'entry_points' 		=> $tournament->entry_points,
						'created_on' 		=> time(),
						'modified_on' 		=> time()	
	       );   
	 
				$this->tableGateway->insert($data);
				return $this->tableGateway->getLastInsertValue();
				
	
    }

    public function deleteTournament($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateTournament(Tournament $tournament , $id)
	{
		$data = array(		
            'name' 				=> $tournament->name,
			'duration' 			=> $tournament->duration,				
			'start_date' 		=> $tournament->start_date,
			'end_date' 			=> $tournament->end_date,
			'tournament_type' 	=> $tournament->tournament_type,
			'entry_points' 		=> $tournament->entry_points,
			'modified_on' 		=> time()
			
			
       );  
	   
	  
		
		$this->tableGateway->update($data, array('id' => $id));
	}
	
}