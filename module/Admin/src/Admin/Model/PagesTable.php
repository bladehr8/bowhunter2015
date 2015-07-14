<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class PagesTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
       # use a closure to manipulate the Select object 
	   /*
	   $active = 1;
	   $resultSet = $this->tableGateway->select(
		function (Select $select) use ($active) {
        //$select->columns(array('id', 'name', 'type')); 
        $select->order('id DESC'); 
        //$select->where(array('active'=> $active));
    });
	*/
       // return $resultSet;
		
		
		 $adapter = $this->tableGateway->getAdapter();		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('pages');
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
        //if (!$row) {
            //throw new \Exception("Could not find row $id");
        //}
		//var_dump($row);
        return $row;
    }
	
	public function getDataByShortCode($title)
    {
        $title  = trim($title);
        $rowset = $this->tableGateway->select(array('seo_title' => $title));
        $row = $rowset->current();
        if (!$row) {
            //throw new \Exception("Could not find row $id");
			return null;
        }
		else {
					return $row;
		}
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
						'table'   => 'advertisement',
						'field'   => 'name',
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
		
		// username is invalid; print the reason
		//$//messages = $validator->getMessages();
		//foreach ($messages as $message) {
		//echo "$message\n";
		//}

    }

    public function saveData(Pages $pages)
    {
        $data = array(
            'title' 		=> $pages->title,
			'seo_title' 	=> $pages->seo_title,
			'short_note' 	=> $pages->short_note,
			'content' 		=> $pages->content,			
			'active' 		=> $pages->active
       );

			$id = (int)$pages->id;
			if ($id == 0) {
				//$data['field_image'] = $pages->field_image ; 
				if( $pages->field_image != null ) $data['field_image'] = $pages->field_image ; 
				$data['modified_on'] = $pages->modified_on ;
				$data['created_on'] = $pages->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {
					if( $pages->field_image != null ) $data['field_image'] = $pages->field_image ; 
					$data['modified_on'] = $pages->modified_on ;
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
		$select->from('pages');	
		
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