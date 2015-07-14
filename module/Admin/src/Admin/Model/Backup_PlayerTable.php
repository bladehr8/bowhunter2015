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

    public function deletePlayer($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}