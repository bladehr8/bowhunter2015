<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Validator\Db\RecordExists;
  

class BankReportTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll( $paymentStatus = null , $dateString = null )
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
		 $select->from('payments_history');
		 //$select->columns(array( 'id','user_id', 'add_id', 'date','status', 'totalClick' => new \Zend\Db\Sql\Expression('SUM(advertisement_logs.click_count)'), 'totalView' => new \Zend\Db\Sql\Expression('SUM(advertisement_logs.view_count)')  ));
		 $select->join('player', 'payments_history.player_id = player.id ' , array('name' ,'email'),$type = self::JOIN_LEFT);
		// $select->join('bank', 'payments_history.bank_id = bank.id ' , array('name'),$type = self::JOIN_LEFT);
		 
		 //$select->where(array('date'=> $active));
			if( !is_null($dateString) )
			{
				$predicate 		= new  \Zend\Db\Sql\Where();
				$str_arr  		= explode(" - " , $dateString );
				$startDate 		=  strtotime($str_arr[0]) ;
				$endDate 		=  strtotime($str_arr[1]) ;
				$select->where($predicate->greaterThanOrEqualTo('created_on',$startDate ) );
				$select->where($predicate->lessThanOrEqualTo('created_on',$endDate ) );
				//02/03/2015 - 02/18/2015
			}
			if($paymentStatus != null) {
				$select->where(array('status' => $paymentStatus));
			}
		 
		 $select->order('created_on DESC');
		 //$select->group('add_id');
 
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
	


    public function saveData(BankReport $bankreport)
    {
        $data = array(
            'player_id' 				=> $bankreport->player_id,
			'bank_id' 					=> $bankreport->bank_id,
			'pay_amount' 				=> $bankreport->pay_amount,
			'product_name' 				=> $bankreport->product_name,
			'transaction_id' 			=> $bankreport->transaction_id,
			'bucks' 					=> $bankreport->bucks,
			'gold_coins' 				=> $bankreport->gold_coins,			
			'status' 					=> $bankreport->status
       );

			$id = (int)$bankreport->id;
			if ($id == 0) {
				//$data['modified_on'] = $advertisement->modified_on ;
				$data['created_on'] = $bankreport->created_on ;
				$this->tableGateway->insert($data);
				$id = $this->tableGateway->getLastInsertValue(); 
			 } else {
				if ($this->getData($id)) {
					//$data['modified_on'] = $advertisement->modified_on ;
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
			
			return $id;
	   
    }


    public function deleteData($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	/*
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
	*/
	
	
	
	
	
	
	
	
	
	
	
	# Insert Data into "advertisement_logs" table based on missionID
	/*
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
	
	*/
	
	
	
	
	
	
	
	
	
	
}