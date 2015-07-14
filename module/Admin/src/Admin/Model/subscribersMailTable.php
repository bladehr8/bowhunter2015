<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class subscribersMailTable
{
 protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

public function insertsubscribersMail(subscribersMail $data) 
{
        $mailContentValue =  $data->mailContent; 
        $data = array(
            'mailContent' => $data->mailContent,
			'date' => $data->date,
			'time' => $data->time,
            'groupOfUser'  => $data->groupOfUser,
			 
        );
        
        $id = (int)$data->id;
        if ($id == 0 && $mailContentValue !='') {
          $result =   $this->tableGateway->insert($data);
           
        } 
        
    
}

}
?>