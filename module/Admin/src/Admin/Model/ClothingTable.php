<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class ClothingTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll($setid1)
    {
		
		$adapter = $this->tableGateway->getAdapter();		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('clothing');
		
		 $select->join('attributeset', 'clothing.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1) && $setid1 > 0) {
			$setid1 = (int) $setid1 ; 
			$select->where('clothing.setId = '.$setid1);
		 }				
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();			
        return $rowset; 		
    }

    public function getClothing($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistClothingName($name)
	{  
        $rowset = $this->tableGateway->select(array('name' => $name));
        $row = $rowset->current();
        if (!$row) {
					return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveClothing(Clothing $clothing)
    {
        $data = array(
			'setId' 				=> $clothing->setId,
            'name' 					=> $clothing->name,
			'price_gold' 			=> $clothing->price_gold,
			'price_bucks' 			=> $clothing->price_bucks,
			'region_id' 			=> $clothing->region_id	,			
			'deer_panic' 			=> $clothing->deer_panic	,
			'player_facing_name' 	=> $clothing->player_facing_name	,
			'basic_alertness' 		=> $clothing->basic_alertness	,
			'deer_reaction_range' 	=> $clothing->deer_reaction_range				
       );   
		
				$id = (int)$clothing->id;		
				if ($id == 0) {
						$data['created_on'] = $clothing->created_on;
						$this->tableGateway->insert($data);
						return $this->tableGateway->getLastInsertValue();
				} else {
						if ($this->getClothing($id)) {
							$this->tableGateway->update($data, array('id' => $id));
						} else {
							throw new \Exception('Id does not exist');
						}
				}		

    }

    public function deleteClothing($id)
    {	
		$id = (int) $id ; 
		$this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateClothing($data , $id)
	{
		$this->tableGateway->update($data, array('id' => $id));
	}
	
}