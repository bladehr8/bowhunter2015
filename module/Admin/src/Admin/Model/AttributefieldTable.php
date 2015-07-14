<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class AttributefieldTable
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
		//$id  = (int) $id;
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('attributefield');
		 $select->join('attributeset', 'attributefield.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('attributefield.setId = '.$setid1);
		 }
				
		$statement = $sql->prepareStatementForSqlObject($select);
				
		$rowset   = $statement->execute();
			
        return $rowset; 
		
    }

    public function getAttributefield($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistAttributefieldName($attributefieldname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $attributefieldname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveAttributefield(Attributefield $attributefield)
    {
        $data = array(
			'setId' => $attributefield->setId,
            'fieldName' => $attributefield->fieldName,
			'fieldType' => $attributefield->fieldType,
			'active' => $attributefield->active
			
       );
	   
	 // if(!$this->isExistAttributefieldName($attributefield->id))
	 //  { 

			$id = (int)$attributefield->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getAttributefield($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   //}
    }

    public function deleteAttributefield($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateAttributefield($data , $id)
	{
		$this->tableGateway->update($data, array('id' => $id));
	}
	
}