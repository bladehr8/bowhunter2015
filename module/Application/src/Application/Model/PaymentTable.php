<?php
namespace Application\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
  

class PaymentTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select(array('active'=> 1));
        return $resultSet;
    }
	
	public function fetchRowsById($id)
    {
        $resultSet = $this->tableGateway->select(array('id'=> $id));
        return $resultSet;
    }
	

    public function getPayment($id)
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
	


    public function savePayment(Payment $payment)
    {
        $data = array(
            'player_id' 	=> $payment->player_id,
			'bank_id' 		=> $payment->bank_id,			
			'pay_amount' 	=> $payment->pay_amount,
			'game_currency' => $payment->game_currency,
			'created_on' 	=> $payment->created_on,
			'status' 		=> $payment->status,
			'post_data' 		=> $payment->post_data
			
       );
	   


			$id = (int)$payment->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getPayment($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	  
    }
	


    public function deletePayment($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}