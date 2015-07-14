<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Admin\Model\Player;  

class PlayerRankTable
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

    public function getData($id)
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
	


    public function saveData(PlayerRank $playerrank)
    {
        $data = array(
            'name' 			=> $playerrank->name,
            'min_exp' 		=> $playerrank->min_exp,
            'max_exp' 		=> $playerrank->max_exp,            
			'active' 		=> $playerrank->active
			
       );
	   

			$id = (int)$playerrank->id;
			if ($id == 0) {
				$data['field_icon'] = $playerrank->field_icon; 
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {
					if($playerrank->field_icon != null ) $data['field_icon'] = $playerrank->field_icon; 
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   
    }

    public function deleteData($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}