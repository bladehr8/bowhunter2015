<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class ScreenShotsTable
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
		 $select->from('screen_shots');
		 $select->order('id DESC'); 
		 //$select->join('advertisement_type', 'advertisement.advertisement_type_id = advertisement_type.id ' , array('adType'=> 'name'),$type = self::JOIN_LEFT);
 
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
	
	public function isExistName($name)
	{  
		$valid = true;
		$name 	= trim($name);		
		$adapter	= $this->tableGateway->getAdapter();
		//$adapter = null;
		 if (null === $adapter) {
			throw new \Exception('No database adapter present');
		}

		$validator = new RecordExists(
						array(
						'table'   => 'screen_shots',
						'field'   => 'title',
						'adapter' => $adapter
						)

		);

		// We still need to set our database adapter
		$validator->setAdapter($adapter);
		// Validation is then performed as usual
		if ($validator->isValid($name)) {
					$valid = true;
		} else {	
					$valid = false;			
		}		
		return $valid;
    }

    public function saveData(ScreenShots $screenshots)
    {
        $data = array(
            'title' 		=> $screenshots->title,
			'short_note' 	=> $screenshots->short_note,				
			'active' 		=> $screenshots->active
       );

			$id = (int)$screenshots->id;
			if ($id == 0) {
				$data['field_image'] = $screenshots->field_image ; 				
				$data['created_on'] = $screenshots->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {
					if( $screenshots->field_image != null ) $data['field_image'] = $screenshots->field_image ; 					
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
	
	
	public function isDuplicateEntry($add_id ) 
	{
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('screen_shots');	
		
		if(isset($add_id) && $add_id > 0)
		{
			$add_id = (int) $add_id;
			$select->where(array( 'id' =>$add_id  ));
		}
		//$select->where(array('active' => 1));		
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();	
		$row = $rowset->current();
		if (!$row) 
				return false;  
		else 
				return true;

	}
	

	
	
	
}