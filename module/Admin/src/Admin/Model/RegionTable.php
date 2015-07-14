<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
  

class RegionTable
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
	
	public function fetchRowsById($id)
    {
        $resultSet = $this->tableGateway->select(array('parent_id'=> $id));
        return $resultSet;
    }
	

    public function getRegion($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        //if (!$row) {
            //throw new \Exception("Could not find row $id");
        //}
		//var_dump($row);
        return $row;
    }
	
	public function isExistRegionName($regionName)
	{  
        $rowset = $this->tableGateway->select(array('name' => $regionName));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveRegion(Region $region)
    {
        $data = array(
            'name' => $region->name,
			'short_key' => $region->short_key,
			'require_xp_level' => $region->require_xp_level,			
			'created_on' => $region->created_on,
			'active' => $region->active
			
       );
	   
	   if(!$this->isExistRegionName($region->id))
	   {

			$id = (int)$region->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getRegion($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   }
    }
	
	public function updateRegion(Region $region)
    {
        $data = array(
            'name' => $region->name,
			'short_key' => $region->short_key,
			'require_xp_level' => $region->require_xp_level,
			'active' => $region->active
			
       );

			$id = (int)$region->id;
			
			if ($id == 0) {
				$this->tableGateway->insert($data);
				 $id = $this->tableGateway->getLastInsertValue(); //Add this line
			 } else {
				if ($this->getRegion($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   return $id; // Add Return
    }
	
	
	
	
	
	
	
	
	
	

    public function deleteRegion($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}