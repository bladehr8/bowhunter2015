<?php
namespace Admin\Factory\Model;


use Zend\Db\TableGateway\TableGateway;
//use Zend\Db\Adapter\Adapter;
//use Zend\Db\ResultSet\ResultSet;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Admin\Model\Player;  

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Admin\Model\HaircolorTable;
use Admin\Model\Haircolor;
 
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;









class HaircolorTable
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

    public function getHaircolor($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
		//var_dump($row);
        return $row;
    }
	
	public function isExistHaircolorName($haircolorname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $haircolorname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveHaircolor(Haircolor $haircolor)
    {
        $data = array(
            'name' => $haircolor->name,
			'active' => $haircolor->active
			
       );
	   
	   if(!$this->isExistHaircolorName($haircolor->id))
	   {

			$id = (int)$haircolor->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getHaircolor($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   }
    }
	
	public function updateHaircolor(Haircolor $haircolor)
    {
        $data = array(
            'name' => $haircolor->name,
			'active' => $haircolor->active
			
       );

			$id = (int)$haircolor->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getHaircolor($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   
    }
	
	
	
	
	
	
	
	
	
	

    public function deleteHaircolor($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}