<?php
namespace Admin\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect; 

class TournamentmissionTable
{
    protected $tableGateway;
	const JOIN_LEFT = 'left';
    public function __construct(TableGateway $tableGateway)
    { 
		$this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
       // $resultSet = $this->tableGateway->select();
        //return $resultSet;
		
		$adapter = $this->tableGateway->getAdapter();
		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('tournamentmission');
		/* $select->join('attributeset', 'tournamentmission.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('tournamentmission.setId = '.$setid1);
		 } */
				
		$statement = $sql->prepareStatementForSqlObject($select);
				
		$rowset   = $statement->execute();
		
		//echo "<pre>";
		//print_r($rowset);
		//exit();
			
        return $rowset; 
		
    }
	
	
	public function regionList()
    {
       // $resultSet = $this->tableGateway->select();
        //return $resultSet;
		
		$adapter = $this->tableGateway->getAdapter();
		
		 $sql       = new Sql($adapter);
		 $select    = $sql->select();
		 $select->from('region');
		/* $select->join('attributeset', 'tournamentmission.setId = attributeset.id ' , array('setName'=> 'name'),$type = self::JOIN_LEFT);
		 if(isset($setid1))
		 {
		 $select->where('tournamentmission.setId = '.$setid1);
		 } */
				
		$statement = $sql->prepareStatementForSqlObject($select);
				
		$rowset   = $statement->execute();
		//$row = $rowset->current();
		
		
			
        return $rowset; 
		
    }

    public function getTournamentmission($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function isExistTournamentmissionName($tournamentmissionname)
	{  
        $rowset = $this->tableGateway->select(array('name' => $tournamentmissionname));
        $row = $rowset->current();
        if (!$row) {
		return false;
            //throw new \Exception("Could not find row $id");
        }
        return true;
    }

    public function saveTournamentmission(Tournamentmission $tournamentmission)
    {
        $data = array(		
            'name' => $tournamentmission->name,
			'regionType' => $tournamentmission->regionType,
			'startDate' => $tournamentmission->startDate,
			'endDate' => $tournamentmission->endDate,
			'tournamentmissionType' => $tournamentmission->tournamentmissionType,
			'minPointForEntry' => $tournamentmission->minPointForEntry,
			'createOn' => time(),
			'modifyOn' => time()
			
			
			
       );   
	 
				$this->tableGateway->insert($data);
				return $this->tableGateway->getLastInsertValue();
				
	
    }

    public function deleteTournamentmission($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
	
	public function updateTournamentmission(Tournamentmission $tournamentmission , $id)
	{
		$data = array(		
            'name' => $tournamentmission->name,
			'regionType' => $tournamentmission->regionType,
			'startDate' => $tournamentmission->startDate,
			'endDate' => $tournamentmission->endDate,
			'tournamentmissionType' => $tournamentmission->tournamentmissionType,
			'minPointForEntry' => $tournamentmission->minPointForEntry,
			'modifyOn' => time()
			
			
       );  
	   
	  
		
		$this->tableGateway->update($data, array('id' => $id));
	}
	
}