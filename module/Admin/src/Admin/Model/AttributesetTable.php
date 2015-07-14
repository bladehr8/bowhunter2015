<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
//use Zend\Db\Adapter\Adapter;
//use Zend\Db\ResultSet\ResultSet;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Admin\Model\Player;  

class AttributesetTable
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
	
	public function fetchAllByType($typedId)
	{
		 $typedId  	= (int) $typedId;
	     $resultSet = $this->tableGateway->select(array('attribute_type' => $typedId));
			return $resultSet;
	}

    public function getAttributeset($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistAttributesetName($attributesetname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $attributesetname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveAttributeset(Attributeset $attributeset)
    {
        $data = array(
				'name' 				=> $attributeset->name,
				'active' 			=> $attributeset->active,
				'attribute_type' 	=> $attributeset->attribute_type			
				);
	   
	   if(!$this->isExistAttributesetName($attributeset->id))
	   {

			$id = (int)$attributeset->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getAttributeset($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   }
    }

    public function deleteAttributeset($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateAttributeset($data , $id)
	{
		$this->tableGateway->update($data, array('id' => $id));
	}
	
}