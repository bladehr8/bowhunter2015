<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class MissionsTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
	public $infoArray = array();
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }
	public function fetchAllById($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('tournament_has_missions');
		 $select->join('missions', 'tournament_has_missions.missions_id = missions.id ' , array('*'),$type = self::JOIN_LEFT);
		 if(isset($id))
		 {
			$select->where('tournament_has_missions.tournament_id = '.$id);
			$select->where('tournament_has_missions.active = 1');	
		 } 
			
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
        return $rowset; 
	}
	
    public function fetchAll()
    {	
		$adapter = $this->tableGateway->getAdapter();
		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('missions');
		/* $select->join('attributeset', 'tournamentmission.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('tournamentmission.setId = '.$setid1);
		 } */
		$select->where('missions.active = 1');		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
        return $rowset; 	
    }
	
    public function fetchAllDear($id = null)
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
		 
		 if(isset($id) && $id > 0) {
			$id = (int) $id ; 
			$select->where('deer_information.active = 1');
		 }	
		$select->where('deer_information.active = 1');		 
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();	
		
		//$resultSet = new ResultSet();
		//$resultSet->initialize($rowset);
		//print_r($resultSet->toArray());
		//return $resultSet->toArray();		
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
		/* $select->join('attributeset', 'tournamentmission.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('tournamentmission.setId = '.$setid1);
		 } */
				
		$statement = $sql->prepareStatementForSqlObject($select);
				
		$rowset   = $statement->execute();
		//$row = $rowset->current();
		
		
			
        return $rowset; 
		
    }

    public function getMission($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
    public function getMissionDetails($id = null)
    {
		// $resultSet = $this->tableGateway->select();
        //return $resultSet;		
		$adapter = $this->tableGateway->getAdapter();		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		$select->from('missions');
		$select->join('tournament_has_missions', 'missions.id = tournament_has_missions.missions_id ' , array('tournament_id'),$type = self::JOIN_LEFT);
		$select->join('hunt_suggestion', 'missions.hunt_suggestion_id = hunt_suggestion.id ' , array('suggestionName'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('shorthand_target_type', 'missions.shorthand_target_type_id = shorthand_target_type.id ' , array('targetName'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('hunt_type', 'missions.hunt_types_id = hunt_type.id ' , array('huntType'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('player_position', 'missions.player_position_id = player_position.id ' , array('position'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('region', 'missions.region_id = region.id ' , array('regionName'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('light_conditions', 'missions.light_conditions_id = light_conditions.id ' , array('lightCondition'=> 'name'),$type = self::JOIN_LEFT); 
		$select->join('kill_type', 'missions.kill_type_id = kill_type.id ' , array('killType'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('missions_objectives', 'missions.mission_objectives_id = missions_objectives.id ' , array('objectives'=> 'name'),$type = self::JOIN_LEFT);
		   
		   
		   
		 if(isset($id) && $id > 0) {
			$id = (int) $id ; 
			$select->where('missions.id = '.$id);
		 }	
		$select->where('missions.active = 1');		 
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();	
		$row 	= $rowset->current();
	    if (!$row) {
            throw new \Exception("Could not find row $id");
        }
		
		
		
		 
		//$resultSet = new ResultSet();
		//$resultSet->initialize($rowset);
		//print_r($resultSet->toArray());
		//return $resultSet->toArray();		
        return $row; 		
    }	
	
	
	
	
	
	
	
	
	public function isExistTournamentmissionName($tournamentmissionname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $tournamentmissionname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveMission(Missions $mission)
    {
        $data = array(
		
			'hunt_suggestion_id' => $mission->hunt_suggestion_id,
			'shorthand_target_type_id' => $mission->shorthand_target_type_id,
			'hunt_types_id' => $mission->hunt_types_id,
			'player_position_id' => $mission->player_position_id,
			'region_id' => $mission->region_id,
			'light_conditions_id' => $mission->light_conditions_id,
			'kill_type_id' => $mission->kill_type_id,
			'mission_objectives_id' => $mission->mission_objectives_id,
            'name' => $mission->name,
			'minimum_points_required' => $mission->minimum_points_required,
			'duration' => $mission->duration,
			'recommended_gear' => $mission->recommended_gear,
			'difficulty_level' => $mission->difficulty_level,
			'no_more_hunts' => $mission->no_more_hunts,			
			'kill_for_success' => $mission->kill_for_success,
			'premium_currency_rewarded' => $mission->premium_currency_rewarded,
			'normal_currency_rewarded' => $mission->normal_currency_rewarded,
			'xp_rewarded' => $mission->xp_rewarded,			
			'active' => $mission->active			
       );   
	 
				//$this->tableGateway->insert($data);
				//return $this->tableGateway->getLastInsertValue();
				
		$id = (int)$mission->id;
        if ($id == 0) {
			$data['created_on'] = time();
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getMission($id)) {
                $this->tableGateway->update($data, array('id' => $id));
				return $id;
            } else {
                throw new \Exception('Form id does not exist');
            }
        }	
				
				
				
				
				
				
				
	
    }

    public function deleteMission($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateTournamentmission(Tournamentmission $tournamentmission , $id)
	{
		$data = array(		
            'name' => $tournamentmission->name,
			'regionType' => $tournamentmission->regionType,
			'startDate' => $tournamentmission->startDate,
			'endDate' => $tournamentmission->endDate,
			'tournamentmissionType' => $tournamentmission->tournamentmissionType,
			'minPointForEntry' => $tournamentmission->minPointForEntry,
			'modifyOn' => time()
			
			
       );  
	   
	  
		
		$this->tableGateway->update($data, array('id' => $id));
	}
	
	
	public function saveTournamentHasMissions($data)
	{
		$adapter = $this->tableGateway->getAdapter();
		$sql       = new Sql($adapter);
		$insert    = $sql->insert('tournament_has_missions');
		
		
		$insert->values($data);
				
		$statement = $sql->prepareStatementForSqlObject($insert);				
		$results    = $statement->execute();			
		$id = $adapter->getDriver()->getLastGeneratedValue();		
				
		return $id;	
	}
	
	public function saveMissionsHasDeerinfo($data)
	{
		$adapter = $this->tableGateway->getAdapter();
		$sql       = new Sql($adapter);
		$insert    = $sql->insert('missions_has_deer_information');	
		$insert->values($data);
				
		$statement = $sql->prepareStatementForSqlObject($insert);				
		$results    = $statement->execute();			
		$id = $adapter->getDriver()->getLastGeneratedValue();		
				
		return $id;	
	}

	public function deleteMissionsHasDeerinfo($id)
	{
		$adapter = $this->tableGateway->getAdapter();
		$sql       = new Sql($adapter);
		$delete    = $sql->delete('missions_has_deer_information');
		$delete->where(array('mission_id' => $id));				
		$statement 	= $sql->prepareStatementForSqlObject($delete);				
		$results    = $statement->execute();			
		if($results) return true;
	}

	#	 Delete Data into "missions_has_hunt_suggestions" table based on missionID
	public function deleteMissionsHasHuntSuggestions($id){
	
		$adapter = $this->tableGateway->getAdapter();
		$sql       = new Sql($adapter);
		$delete    = $sql->delete('missions_has_hunt_suggestions');
		$delete->where(array('mission_id' => $id));				
		$statement 	= $sql->prepareStatementForSqlObject($delete);				
		$results    = $statement->execute();			
		if($results) return true;

	}

	
	# Insert Data into "missions_has_hunt_suggestions" table based on missionID
	public function saveMissionsHasHuntSuggestions($data)
	{
		$adapter = $this->tableGateway->getAdapter();
		$sql       = new Sql($adapter);
		$insert    = $sql->insert('missions_has_hunt_suggestions');


		$insert->values($data);

		$statement = $sql->prepareStatementForSqlObject($insert);				
		$results    = $statement->execute();			
		$id = $adapter->getDriver()->getLastGeneratedValue();		

		return $id;	
	}
	
	
	
	
	
	
	
	
	
	
		# Load data from "huntTypeLists" - Table
	public function huntTypeLists($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('hunt_type');	
		
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
		if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Hunt Type");
		return $this->infoArray;

	}
	
	
	
	
		# Load data from "huntSuggestionLists" - Table
	public function huntSuggestionLists($id = null , $mode = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('hunt_suggestion');	
		
		if(isset($id) && $id > 0)
		{
			$id = (int) $id;
			$select->where(array('id' => $id ));
		}
		$select->where(array('active' => 1));
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
		
		if(isset($mode) && $mode == 'lists' )
		{
			return $rowset ;
		}
		else{
		
				foreach($rowset as $row){
							$this->infoArray[$row['id']] =  $row['name'];	
				}
				if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Suggestions");
				return $this->infoArray;

		}
		
		


	}
		
	
	
		# Load data from "playerPositionLists" - Table
	public function playerPositionLists($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('player_position');	
		
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
		if(count($this->infoArray) > 0 )  array_unshift($this->infoArray, "Select Player Position");
		return $this->infoArray;

	}	
	
	
	
		# Load data from "shortHandTargetTypeLists" - Table
	public function shortHandTargetTypeLists($id = null)
	{
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('shorthand_target_type');	
		
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
		if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Target Type");
		return $this->infoArray;

	}	
		
	
	# Load data from "regionLists" - Table
	public function regionLists($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('region');	
		
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
		if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Region");		
		return $this->infoArray;

	}		
	
	# Load data from "lightConditionLists" - Table
	public function lightConditionLists($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('light_conditions');	
		
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
		if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Light Conditions");		
		return $this->infoArray;

	}		
	
	
	# Load data from "deerInfoLists" - Table
	public function deerInfoLists($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('deer_information');	
		
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
		if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Region");		
		return $this->infoArray;

	}		
	
	# Load data from "killTypeLists" - Table
	public function killTypeLists($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('kill_type');	
		
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
		if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Kill Type");		
		return $this->infoArray;

	}


	# Load data from "missions_objectives" - Table
	public function missionObjectives($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('missions_objectives');	
		
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
		if(count($this->infoArray) > 0 ) array_unshift($this->infoArray, "Select Objectives");		
		return $this->infoArray;

	}



	# Pull data from "missions_has_hunt_suggestions" - Table
	public function getMissionsHasHuntSuggestions($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('missions_has_hunt_suggestions');	
		
		if(isset($id) && $id > 0)
		{
			$id = (int) $id;
			$select->where(array('mission_id' => $id ));
		}		
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
		
		foreach($rowset as $row){
			$this->infoArray[$row['id']] =  $row['suggestion_id'];	
		}		
		return $this->infoArray;

	}



	# Pull data from "missions_has_hunt_suggestions" - Table
	public function getMissionsHasDeerinfo($id = null)
	{
		$this->infoArray = array();
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('missions_has_deer_information');	
		
		if(isset($id) && $id > 0)
		{
			$id = (int) $id;
			$select->where(array('mission_id' => $id ));
		}		
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
		
		return $rowset;

	}






	
	
	
}