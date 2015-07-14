<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class WeaponattrubutevalueTable
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
		$id  = (int) $id;
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('weaponattrubutevalue');
		 $select->join('attributeset', 'weaponattrubutevalue.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('weaponattrubutevalue.setId = '.$setid1);
		 }
				
		$statement = $sql->prepareStatementForSqlObject($select);
				
		$rowset   = $statement->execute();
			
        return $rowset; 
		
    }

    public function getWeaponattrubutevalue($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistWeaponattrubutevalueName($weaponattrubutevaluename)
	{  
        $rowset = $this->tableGateway->select(array('name' => $weaponattrubutevaluename));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveWeaponattrubutevalue(Weaponattrubutevalue $weaponattrubutevalue)
    {
        $data = array(
			'attribute_field_id' => $weaponattrubutevalue->attribute_field_id,
            'attribute_field_name' => $weaponattrubutevalue->attribute_field_name,
			'productId' => $weaponattrubutevalue->productId,
			'value' => $weaponattrubutevalue->value
			
			
       );   
	 
				$this->tableGateway->insert($data);
				return $this->tableGateway->getLastInsertValue();
				
	
    }

    public function deleteWeaponattrubutevalue($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateWeaponattrubutevalue($data , $id)
	{
		$this->tableGateway->update($data, array('id' => $id));
	}
	
}