<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
//use Zend\Db\Adapter\Adapter;
//use Zend\Db\ResultSet\ResultSet;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Admin\Model\Player;  

class PlayerTable
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

    public function getPlayer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
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
			'gender' => $player->gender,
			'socialType' => $player->socialType,
			'socialId' => $player->socialId,
			'xpPoint' => (int)$player->xpPoint,
			'totalNormalCurrency' => (int)$player->totalNormalCurrency,
			'totalPremiumCurrency' => (int)$player->totalPremiumCurrency,
			'totalEnergy' => (int)$player->totalEnergy,
			'challengeAmoutEscrow' => (int)$player->challengeAmoutEscrow,
       );
	   
	   if(!$this->isExistSocialId($player->socialId))
	   {

			$id = (int)$player->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getPlayer($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Form id does not exist');
				}
			}
	   }
    }

    public function deletePlayer($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}