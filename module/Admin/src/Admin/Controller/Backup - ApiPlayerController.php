<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\View\Model\ViewModel;
use Admin\Model\Player;          // <-- Add this import
use Admin\Form\PlayerForm;       // <-- Add this import
use Admin\Model\PlayerTable; 

use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;



class ApiPlayerController extends AbstractRestfulController
{
	
		protected $playerTable;	
		protected $restApiKey = "AXDFG65432@789GAME#987";

		
		public function getPlayerTable()
		{
			if (!$this->playerTable) {
				$sm = $this->getServiceLocator();
				$this->playerTable = $sm->get('Admin\Model\PlayerTable');
			}
			return $this->playerTable;
		}

		
		
		public function getList()
		{
				# get all players from playerTable 
				$response = array();
				$results 	= $this->getPlayerTable()->fetchAll();
				$data 		= array();
				foreach($results as $result) {
					$data[] = $result;
				}
				
				//return array('data' => $data);
				if(count($data) >0 )
				{
					//$response["success"] = 1;	
					return new JsonModel(array( 'data' => $data ));
				}
				else
				{
					return new JsonModel(array( 'error' => true,'message' => "No Data Found" ));
				}
	
				
		}

		public function get($id)
		{
			# Get Player Details byID
			$response = array();
			$id = (int) $id;
			$dataVal = $this->getPlayerTable()->getPlayer($id);			
			if(!$dataVal)
			{				
				return new JsonModel(array( 'error' => true,'message' => "Player ID $id does not exist " ));
			}	
			else
			{			
				return new JsonModel(array( 'data' => $dataVal ));
			}

		}

		public function create($data)
		{
						#code...
						$response = array();
						$request = $this->getRequest();
						if ($request->isPost())
						{
							$player = new Player();
							$apikey = $data['apikey'];
							
							$socailId 	= $data['socialId'];
							$socailId  = (int) $socailId;
							$name 		= trim($data['name']);
							$email 		= trim($data['email']);
							$gender		= $data['gender'];
							
							
							$dataArr['name']		=	$name;
							$dataArr['email']		=	$email;
							
							$dataArr['gender']		=	$gender;
							$dataArr['socialType']	= 	$data['socialType'];				
							$dataArr['socialId']	=	$socailId;								
							$dataArr['xpPoint'] 	= 0 ; 
							$dataArr['totalNormalCurrency'] 	= 0 ; 
							$dataArr['totalPremiumCurrency'] 	= 0 ; 
							$dataArr['totalEnergy'] 	= 0 ; 
							$dataArr['challengeAmoutEscrow'] 	= 0 ; 
							
							if( ( empty($apikey) ) ||  ($apikey != $this->restApiKey) ){
								//$response["error"] = 1;	
								//$response["error_msg"] = "Authentication failure ! Invalid API-KEY .";	
								//return new JsonModel(array( 'ack' => $response, ));
								return new JsonModel(array( 'error' => true,'message' => "Authentication failure ! Invalid API-KEY ." ));
								exit();
							}							
							elseif(empty($socailId) || $socailId <= 0 )
							{
								//$response["error"] = 2;	
								//$response["error_msg"] = "Invalid  Social-ID  $socailId";								
								return new JsonModel(array( 'error' => true, 'message' => "Invalid  Social-ID  $socailId" ));
								exit();
							}
							elseif(empty($name)){
								//$response["error"] = 2;	
								//$response["error_msg"] = "Name can not be empty !";								
								return new JsonModel(array( 'error' => true, 'message' => "Name can not be empty" ));
								exit();
							}

							elseif(empty($email)){
								//$response["error"] = 2;	
								//$response["error_msg"] = "Email can not be empty !";								
								return new JsonModel(array( 'error' => true, 'message' => "Email can not be empty" ));
								exit();
							} 
							else {							
									$player->exchangeArray($dataArr);						
									$id =  $this->getPlayerTable()->savePlayer($player);
							}
							
							
							if(!empty($id) && $id > 0 )
							{
								$dataVal = $this->getPlayerTable()->getPlayer($id);									
								return new JsonModel( array('data' => $dataVal ) );
							}
							else
							{							
								return new JsonModel(array( 'error' => true, 'message' => "Duplicate Entry of Social-ID #$socailId" ));
							}
	
						}

					

		}

		public function update($id, $data)
		{
				
				$data['id'] = $id;

				
				$bank = $this->getBankTable()->getBank($id);
				$form = new BankForm();
				$form->bind($bank);
				$form->setInputFilter($bank->getInputFilter());
				$form->setData($data);
				if ($form->isValid()) {
					
					$dataArr['id']		=	$data['id'];
					$dataArr['name']	=	$data['name'];
					$dataArr['active']	= 	$data['active'];				
					$dataArr['price']	=	$data['price'];		
				
					
					$bank->exchangeArray($dataArr);
					$returnid = $this->getBankTable()->updateBank($bank);
					
					
					
					
					
				}

				
				
				return new JsonModel(array(
										'data' => $returnid,
					));

			
		}

		public function delete($id)
		{
			# code...
			
		$this->getBankTable()->deleteBank($id);

		return new JsonModel(array(
					'data' => 'deleted',
					));
			
			
			
			
		}

	

}

