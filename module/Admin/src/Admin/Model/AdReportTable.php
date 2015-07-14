<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class AdReportTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll( $adStatus = null , $searchString = null )
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
		 $select->from('advertisement_logs');
		 $select->columns(array( 'id','user_id', 'add_id', 'date','status', 'totalClick' => new \Zend\Db\Sql\Expression('SUM(advertisement_logs.click_count)'), 'totalView' => new \Zend\Db\Sql\Expression('SUM(advertisement_logs.view_count)')  ));
		 $select->join('player', 'advertisement_logs.user_id = player.id ' , array('name' ,'email'),$type = self::JOIN_LEFT);
		 //$select->join('player', 'advertisement_logs.user_id = player.id ' , array('name' ,'email'),$type = self::JOIN_LEFT);
		 
		 //$select->where(array('date'=> $active));
			if( $searchString != null )
			{
				$predicate 		= new  \Zend\Db\Sql\Where();
				$str_arr  		= explode(" - " , $searchString );
				$startDate 		=  date("Y-m-d" , strtotime($str_arr[0]) );
				$endDate 		=  date("Y-m-d" , strtotime($str_arr[1]) );
				$select->where($predicate->greaterThanOrEqualTo('date',$startDate ) );
				$select->where($predicate->lessThanOrEqualTo('date',$endDate ) );
				//02/03/2015 - 02/18/2015
			}
			
		if($adStatus != null) {
				$select->where(array('status' => $adStatus));
			}
			
			
			
			

		 
		 $select->order('date DESC');
		 $select->group('add_id');
 
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
	
	public function isExistName($name)
	{  
       // $rowset = $this->tableGateway->select(array('name' => $name));
        //$row = $rowset->current();
       // if (!$row) {
		//return false;
            //throw new \Exception("Could not find row $id");
        //}
        //return true;
		
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

    public function saveData(Advertisement $advertisement)
    {
        $data = array(
            'advertisement_type_id' => $advertisement->advertisement_type_id,
			'duration' => $advertisement->duration,
			'notification' => $advertisement->notification,
			'image_1' => $advertisement->image_1,
			'content' => $advertisement->content,
			'image_2' => $advertisement->image_2,
			'url' => $advertisement->url,			
			'active' => $advertisement->active
       );

			$id = (int)$advertisement->id;
			if ($id == 0) {
				$data['modified_on'] = $advertisement->modified_on ;
				$data['created_on'] = $advertisement->created_on ;
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getData($id)) {
					$data['modified_on'] = $advertisement->modified_on ;
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
	
	
	public function isDuplicateEntry($add_id , $user_id , $date) {
		$adapter = $this->tableGateway->getAdapter();
		$this->infoArray = array();
		
		$sql       = new Sql($adapter);
		$select    = $sql->select();
		$select->from('advertisement_logs');	
		
		if(isset($add_id) && $add_id > 0)
		{
			$add_id = (int) $add_id;
			$select->where(array('user_id' => $user_id  , 'add_id' =>$add_id  , 'date' => $date ));
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
	
	
	
	
	
	
	
	
	
	
	
	
	# Insert Data into "advertisement_logs" table based on missionID
	public function saveAdvertisementLogs($data)
	{
		$adapter = $this->tableGateway->getAdapter();
		$sql       = new Sql($adapter);
		
		$user_id	= $data['user_id'];
		$add_id		= $data['add_id'];
		$date		= $data['date'];
		
		if( $this->isDuplicateEntry($add_id , $user_id , $date) )
		{
			$dataAltered      = array('click_count' => new \Zend\Db\Sql\Expression('click_count + 1') ,'view_count' => new \Zend\Db\Sql\Expression('view_count + 1')  ); 
			$update    = $sql->update('advertisement_logs');
			$update->set($dataAltered);
			$update->where(array('user_id' => $user_id  , 'add_id' =>$add_id  , 'date' => $date ));
			$statement = $sql->prepareStatementForSqlObject($update);
			
			
		}
		else
		{		
			$insert    = $sql->insert('advertisement_logs');
			$insert->values($data);
			$statement = $sql->prepareStatementForSqlObject($insert);
		}
		
		$results    = $statement->execute();			
		//$id = $adapter->getDriver()->getLastGeneratedValue();		

		return $results;	
	}
	
	
	
		# Fetch unique users from Advertisement-Id
	public function fetchUsersByAdId( $adId =  null )
    {

		 $adapter = $this->tableGateway->getAdapter();		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('advertisement_logs');
		 $select->columns(	array( 'user_id' => new \Zend\Db\Sql\Expression('DISTINCT( advertisement_logs.user_id ) ') ));
		 $select->join('player', 'advertisement_logs.user_id = player.id ' , array('name' ,'email' , 'socialId' ,'socialType'),$type = self::JOIN_LEFT);

		if($adId != null) {
				$select->where(array('add_id' => $adId));
			}


 
		$statement = $sql->prepareStatementForSqlObject($select);				
		$rowset   = $statement->execute();		
        
		$resultSet = new ResultSet();
		$resultSet->initialize($rowset);
		return $resultSet; 
		
    }
	
	
	
	
	
	
	
	
	
}