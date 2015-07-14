<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Admin\Model\Player;  
use Zend\Paginator\Paginator;

class PlayerLevelStatusTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated=false)
    {
         if($paginated) {
            // create a new Select object for the table album
            $select = new Select('player');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Player());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
                // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGateway->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
		
		$resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getPlayerLevelStatus($id)
    {
		
		$adapter = $this->tableGateway->getAdapter();
		$id  = (int) $id;
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('player_level_staus');
		//$select->join('player', 'player.id = player_level_staus.user_id ' , array('*'),$type = self::JOIN_LEFT);		
		$select->where(array('id' => $id ));
		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		$row 	  = $rowset->current();
        return $row; 

    }
	
	public function isExistSocialId($socialId)
    {   $id  = $socialId;
        $rowset = $this->tableGateway->select(array('socialId' => $id));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function savePlayerLevelStatus(PlayerLevelStatus $playerlevelstatus)
    {
        $data = array(
            'user_id' 					=> $playerlevelstatus->user_id,
			'levelwin' 					=> $playerlevelstatus->levelwin,
			'region' 					=> $playerlevelstatus->region,
			'hunt_type' 				=> $playerlevelstatus->socialType,
			'progression_hunt_num' 		=> $playerlevelstatus->progression_hunt_num,
			'cash_hunt_num' 			=> (int)$playerlevelstatus->cash_hunt_num,
			'invitational_hunt_num' 	=> (int)$playerlevelstatus->invitational_hunt_num,
			'premium_currency_won' 		=> (int)$playerlevelstatus->premium_currency_won,
			'normal_currency_won' 		=> (int)$playerlevelstatus->normal_currency_won,
			'xp_earned' 				=> (int)$playerlevelstatus->xp_earned,
			'largest_kill' 				=> (int)$playerlevelstatus->largest_kill,
			'longest_kill' 				=> (int)$playerlevelstatus->longest_kill,
			'valid_kills' 				=> (int)$playerlevelstatus->valid_kills,
			'arrows_fired' 				=> (int)$playerlevelstatus->arrows_fired,
			'arrows_broken' 			=> (int)$playerlevelstatus->arrows_broken,			
			'created_on' 				=> time(),
			'active' 					=> $playerlevelstatus->active
       );
	   
	

			$id = (int)$playerlevelstatus->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
				$id = $this->tableGateway->getLastInsertValue(); 
			 } else {
				if ($this->getPlayerLevelStatus($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Form id does not exist');
				}
			}

	   
	   return $id; // Add Return
	   
    }



    public function deletePlayer($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}