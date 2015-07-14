<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 




  

class SuggestionTable
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
        $rowset = $this->tableGateway->select(array('id'=> $id , 'active' => 1));
		$row = $rowset->current();
        return $row;
    }
	

    public function getSuggestion($id)
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
	
	public function isExistName($name)
	{  
        $rowset = $this->tableGateway->select(array('name' => $name));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveSuggestion(Suggestion $suggestion)
    {
        $data = array(
            'name' 				=> $suggestion->name,
			
			'suggestion_available' 	=> $suggestion->suggestion_available,
			'hitpower' 				=> $suggestion->hitpower,
			'sight' 				=> $suggestion->sight,
			'nsight' 				=> $suggestion->nsight,
			'infra_red' 			=> $suggestion->infra_red,
			'thermal' 				=> $suggestion->thermal,
			'stabilizer' 			=> $suggestion->stabilizer,
			'camouflage_dress' 		=> $suggestion->camouflage_dress,
			
			'created_on' 			=> $suggestion->created_on,
			'active' 				=> $suggestion->active			
       );
	   

		$id = (int)$suggestion->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		 } else {
			if ($this->getSuggestion($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Id does not exist');
			}
		}
		   
    }
	


    public function deleteSuggestion($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}