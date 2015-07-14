<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class NewsTable
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
		 $select->from('news');
		 $select->order('created_on DESC');  
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
        return $row;
    }
	
	public function isExistName()
	{  	
		$adapter	= $this->tableGateway->getAdapter();	
		 if (null === $adapter) {
				throw new \Exception('No database adapter present');
		}		
		$validator = new RecordExists( array( 	'table'   => 'news', 'field'   => 'title', 'adapter' => $adapter) );
		// We still need to set our database adapter
		$validator->setAdapter($adapter);	
		return $validator;	
    }

    public function saveData(News $news)
    {
        $data = array(
            'title' 		=> $news->title,
			'news_content' 	=> $news->news_content,
			'external_url' 	=> $news->external_url,			
			'active' 		=> $news->active
       );

			$id = (int)$news->id;
			if ($id == 0) {				
				$data['modified_on'] = $news->modified_on ;
				$data['created_on']  = $news->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {					
					$data['modified_on'] = $news->modified_on ;
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