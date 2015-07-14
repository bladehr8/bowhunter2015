<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
  

class LightTable
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
        $resultSet = $this->tableGateway->select(array('id'=> $id));
        return $resultSet;
    }
	

    public function getLight($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
       // if (!$row) {
           // throw new \Exception("Could not find row $id");
       // }
		//var_dump($row);
        return $row;
    }
	
	public function isExistName($regionName)
	{  
        $rowset = $this->tableGateway->select(array('name' => $regionName));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveLight(Light $light)
    {
        $data = array(
            'name' 				=> $light->name,		
			'created_on' 		=> $light->created_on,
			'active' 			=> $light->active			
       );
	   
		   if(!$this->isExistName($light->name))
		   {
					$id = (int)$light->id;
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
	
	public function updateLight(Light $light)
    {
        $data = array(
            'name' => $light->name,
			'active' => $light->active
			
       );

			$id = (int)$light->id;
			
			if ($id == 0) {
				$this->tableGateway->insert($data);
				 $id = $this->tableGateway->getLastInsertValue(); //Add this line
			 } else {
				if ($this->getLight($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   return $id; // Add Return
    }
	
	
	
	
	
	
	
	
	
	

    public function deleteLight($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}