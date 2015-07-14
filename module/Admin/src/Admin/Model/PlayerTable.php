<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
 use Zend\Db\Sql\Where;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Predicate\Like;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Admin\Model\Player;  
use Zend\Paginator\Paginator;

class PlayerTable
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

    public function getPlayer($id)
    {
		
		$adapter = $this->tableGateway->getAdapter();
		$id  = (int) $id;
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('player');
		$select->join('haircolor', 'haircolor.id = player.hairColorId ' , array('haircolor'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('skincolor', 'skincolor.id = player.skincolorId ' , array('skincolor'=> 'name'),$type = self::JOIN_LEFT);
		// $select->columns(array('name'));
		$select->where('player.id = '.$id);

		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		$row 	  = $rowset->current();
		//if (!$row) {
           // throw new \Exception("Could not find row $id");
        //} 
        return $row; 
		
		
		
    }
    
    
    
    public function getPlayerByName($name) #Added by Dibyendu Konar for demo version check of ApiPlayerActivityDemoController for webservice
    {
		
		$adapter = $this->tableGateway->getAdapter();
	
        $where = new Where();
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('player');
		$select->join('haircolor', 'haircolor.id = player.hairColorId ' , array('haircolor'=> 'name'),$type = self::JOIN_LEFT);
		$select->join('skincolor', 'skincolor.id = player.skincolorId ' , array('skincolor'=> 'name'),$type = self::JOIN_LEFT);
		// $select->columns(array('name'));
       
        $where->like('player.name', '%' . $name . '%');
		$select->where($where);

		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		$row 	  = $rowset->current();
		//if (!$row) {
           // throw new \Exception("Could not find row $id");
        //} 
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

    public function savePlayer(Player $player)
    {
        $data = array(
            'name' => $player->name,
			'email' => $player->email,
			'gender' => $player->gender,
			'socialType' => $player->socialType,
			'socialId' => $player->socialId,
			'xpPoint' => (int)$player->xpPoint,
			'totalNormalCurrency' => (int)$player->totalNormalCurrency,
			'totalPremiumCurrency' => (int)$player->totalPremiumCurrency,
			'totalEnergy' => (int)$player->totalEnergy,
			'challengeAmoutEscrow' => (int)$player->challengeAmoutEscrow,
			'createOn' => time(),
			'modifyOn' => time()
       );
	   
	   if(!$this->isExistSocialId($player->socialId))
	   {

			$id = (int)$player->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
				$id = $this->tableGateway->getLastInsertValue(); 
			 } else {
				if ($this->getPlayer($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Form id does not exist');
				}
			}
	   }
	   else
	   {
		$id = 0;
	   }
	   
	   return $id; // Add Return
	   
    }
	
	
	# Update Player
	
	public function updatePlayer(Player $player)
    {
        $data = array(
            'xpPoint' => $player->xpPoint,
			'totalPremiumCurrency' => $player->totalPremiumCurrency,
			'totalNormalCurrency' => $player->totalNormalCurrency,
			'totalEnergy' => $player->totalEnergy,
			'current_region' => $player->current_region,
			
			'progressionHuntNum' => (int)$player->progressionHuntNum,
			'cashHuntNum' => (int)$player->cashHuntNum,
			'invitationalHuntNum' => (int)$player->invitationalHuntNum,
			
			'bowEquipped' => (int)$player->bowEquipped,			
			'arrowEquipped' => (int)$player->arrowEquipped,
			'sightEquipped' => (int)$player->sightEquipped,
			'stabilizerEquipped' => (int)$player->stabilizerEquipped,
			'quiverEquipped' => (int)$player->quiverEquipped,
			'opticsEquipped' => (int)$player->opticsEquipped,
			
			'dressPickId' => (int)$player->dressPickId,
			'hairColorId' => (int)$player->hairColorId,
			'skinColorId' => (int)$player->skinColorId,
			'faceColorId' => (int)$player->faceColorId,
			
			
			'thermalBatteryCount' => (int)$player->thermalBatteryCount,
			'infraredBatteryCount' => (int)$player->infraredBatteryCount,
			'rangefinderBatteryCount' => (int)$player->rangefinderBatteryCount,
			'saltlickCount' => (int)$player->saltlickCount,
			'cornCount' => (int)$player->cornCount,

			'modifyOn' => time()
       );
	   
	  

			$id = (int)$player->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
				$id = $this->tableGateway->getLastInsertValue(); 
			 } else {
				if ($this->getPlayer($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Form id does not exist');
				}
			}
	
	   
	 
	   
    }
	
	
	
	
    public function fetchByEmail($email = null)
    {
		
		$adapter = $this->tableGateway->getAdapter();
		$id  = (int) $id;
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('player');
		
		if($email != null) $select->where(array('email' => $email));
			

		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();
		$row 	  = $rowset->current();
		//if (!$row) {
           // throw new \Exception("Could not find row $id");
        //} 
        return $row; 
		
		
		
    }	
	
	
	
	
	

    public function deletePlayer($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}