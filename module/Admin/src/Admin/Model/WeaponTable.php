<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class WeaponTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll($setid1)
    {
       // $resultSet = $this->tableGateway->select();
        //return $resultSet;
		
		$adapter = $this->tableGateway->getAdapter();
		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('weapon');
		 $select->join('attributeset', 'weapon.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('weapon.setId = '.$setid1);
		 }
				
		$statement = $sql->prepareStatementForSqlObject($select);
				
		$rowset   = $statement->execute();
		
		//echo "<pre>";
		//print_r($rowset);
		//exit();
			
        return $rowset; 
		
    }

    public function getWeapon($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistWeaponName($weaponname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $weaponname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveWeapon(Weapon $weapon)
    {
        $data = array(
			'setId' => $weapon->setId,
            'name' => $weapon->name,
			'price' => $weapon->price
			
			
       );   
	 
				$this->tableGateway->insert($data);
				return $this->tableGateway->getLastInsertValue();
				
	
    }

    public function deleteWeapon($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateWeapon($data , $id)
	{
		$this->tableGateway->update($data, array('id' => $id));
	}
	
}