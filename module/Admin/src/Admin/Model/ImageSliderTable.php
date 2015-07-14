<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class ImageSliderTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
		$adapter 	= $this->tableGateway->getAdapter();		
		$sql       	= new Sql($adapter);
		$select    	= $sql->select();
		
		$select->from('img_sliding_pool');
		$select->order('id DESC'); 
		
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

    public function saveData(ImageSlider $imageslider)
    {
        $data = array(
            'title' 		=>  $imageslider->title,
			'short_note' 	=>  $imageslider->short_note,
			'external_link'	=> 	$imageslider->external_link,
			'active' 		=>  $imageslider->active
       );

			$id = (int)$imageslider->id;
			if ($id == 0) {
				$data['field_image'] 		= $imageslider->field_image ; 				
				$data['created_on'] 		= $imageslider->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {
					if( $imageslider->field_image != null ) $data['field_image'] = $imageslider->field_image ; 					
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