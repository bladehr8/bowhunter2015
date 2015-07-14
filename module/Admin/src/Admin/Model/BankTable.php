<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
  

class BankTable
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
	

    public function getBank($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
       // if (!$row) {
           // throw new \Exception("Could not find row $id");
       // }
		//var_dump($row);
        return $row;
    }
	
	public function isExistBankName($bankName)
	{  
        $rowset = $this->tableGateway->select(array('name' => $bankName));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveBank(Bank $bank)
    {
        $data = array(
            'name' => $bank->name,
			'bucks' => $bank->bucks,			
			'gold_coins' => $bank->gold_coins,
			'price' => $bank->price,
			'created_on' => $bank->created_on,
			'active' => $bank->active
			
       );
	   
	   if(!$this->isExistBankName($bank->id))
	   {

			$id = (int)$bank->id;
			if ($id == 0) {
				$this->tableGateway->insert($data);
			 } else {
				if ($this->getBank($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   }
    }
	
	public function updateBank(Bank $bank)
    {
        $data = array(
            'name' => $bank->name,
			'bucks' => $bank->bucks,			
			'gold_coins' => $bank->gold_coins,
			'price' => $bank->price,			
			'active' => $bank->active
			
       );

			$id = (int)$bank->id;
			
			if ($id == 0) {
				$this->tableGateway->insert($data);
				 $id = $this->tableGateway->getLastInsertValue(); //Add this line
			 } else {
				if ($this->getBank($id)) {
					$this->tableGateway->update($data, array('id' => $id));
				} else {
					throw new \Exception('Id does not exist');
				}
			}
	   return $id; // Add Return
    }

    public function deleteBank($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}