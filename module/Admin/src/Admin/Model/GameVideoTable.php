<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class GameVideoTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
		 $adapter = $this->tableGateway->getAdapter();		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('game_video');
		 $select->order('id DESC'); 
 
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();		
        
		$resultSet = new ResultSet();
		$resultSet->initialize($rowset);
		return $resultSet; 
		
    }
	
	public function fetchRowsById($id)
    {
        $resultSet = $this->tableGateway->select(array('id'=> $id));
        return $resultSet;
    }
	

    public function getData($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
		if($row)  
			return $row;
		else 
			return null ; 
    }
	


    public function saveData(GameVideo $gameVideo)
    {
        $data = array(
            'title' 		=> $gameVideo->title,
			'short_note' 	=> $gameVideo->short_note,
			'youtube_link' => $gameVideo->youtube_link,	
			'active' 		=> $gameVideo->active
       );

			$id = (int)$gameVideo->id;
			if ($id == 0) {					
				$data['created_on'] = $gameVideo->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {									
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