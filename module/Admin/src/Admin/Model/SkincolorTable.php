<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
//use Zend\Db\Adapter\Adapter;
//use Zend\Db\ResultSet\ResultSet;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Admin\Model\Player;  

class SkincolorTable
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

    public function getSkincolor($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistSkincolorName($skincolorname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $skincolorname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveSkincolor(Skincolor $skincolor)
    {
        $data = array(
            'name' => $skincolor->name,
			'active' => $skincolor->active
			
       );
	   
	   if(!$this->isExistSkincolorName($skincolor->id))
	   {

			$id = (int)$skincolor->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getSkincolor($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   }
    }

    public function deleteSkincolor($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}