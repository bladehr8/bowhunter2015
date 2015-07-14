<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class InterviewVideoTable
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
		 $select->from('interview_video');
		 $select->order('created_on DESC'); 
 
		$statement 		= $sql->prepareStatementForSqlObject($select);				
		$rowset   		= $statement->execute();		
        
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
	


    public function saveData(InterviewVideo $interviewVideo)
    {
        $data = array(
            'title' 		=> $interviewVideo->title,
			'short_note' 	=> $interviewVideo->short_note,
			'youtube_link' => $interviewVideo->youtube_link,	
			'active' 		=> $interviewVideo->active
       );

			$id = (int)$interviewVideo->id;
			if ($id == 0) {
				if( $interviewVideo->field_image != null ) $data['field_image'] = $interviewVideo->field_image ; 			
				$data['created_on'] = $interviewVideo->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {
				if( $interviewVideo->field_image != null ) $data['field_image'] = $interviewVideo->field_image ; 				
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