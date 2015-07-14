<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\PlayerLevelStatus;          // <-- Add this import
use Admin\Model\PlayerLevelStatusTable; 

use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;

//class ApiPlayerController extends AbstractRestfulController

class ApiPlayerLevelStatusController extends AbstractActionController
{
	
		protected $playerLevelStatusTable;	
		protected $restApiKey = "AXDFG65432"; // Static/fixed restApiKey

		
		public function getPlayerLevelStatusTable()
		{
			if (!$this->playerLevelStatusTable) {
				$sm = $this->getServiceLocator();
				$this->playerLevelStatusTable = $sm->get('Admin\Model\PlayerLevelStatusTable');
			}
			return $this->playerLevelStatusTable;
		}

		
		


		public function listAction()
		{
			# Get Player Details byID
			$response = array();
			//$id = (int) $id;
			$id = (int) $this->params()->fromRoute('id', 0);
			if( $id > 0)
			{
			
					$dataVal = $this->getPlayerLevelStatusTable()->getPlayerLevelStatus($id);			
					if(!$dataVal)
					{				
						return new JsonModel(array( 'error' => true,'message' => "Player ID $id does not exist " ));
					}	
					else
					{			
						return new JsonModel(array( 'data' => $dataVal ));
					}

			}
			else
			{

				$results 	= $this->getPlayerLevelStatusTable()->fetchAll();
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
			
			
			
			
				exit();

		}

		public function createAction()
		{						
			$response = array();
			$jsondata = $this->params()->fromQuery('json');
			// translate UTF8 to English characters
			$data = iconv('UTF-8', 'ASCII//TRANSLIT', $jsondata);
			$json_data  = json_decode($data );
			//echo json_last_error() ;

			if($json_data) { 

				$user_id 						= $json_data->data[0]->id ; 
				$levelwin 						= $json_data->data[0]->levelwin;
				$region 						= $json_data->data[0]->region; 
				$hunt_type 						= $json_data->data[0]->hunt_type ; 
				$progression_hunt_num 			= $json_data->data[0]->progression_hunt_num ; 
				$cash_hunt_num 					= $json_data->data[0]->cash_hunt_num ; 
				$invitational_hunt_num 			= $json_data->data[0]->invitational_hunt_num ; 
				$premium_currency_won 			= $json_data->data[0]->premium_currency_won ; 
				$normal_currency_won 			= $json_data->data[0]->normal_currency_won ; 
				$xp_earned 						= $json_data->data[0]->xp_earned ; 
				$largest_kill 					= $json_data->data[0]->largest_kill ; 
				$longest_kill 					= $json_data->data[0]->longest_kill ; 
				$valid_kills 					= $json_data->data[0]->valid_kills ; 
				$arrows_fired 					= $json_data->data[0]->arrows_fired ; 
				$arrows_broken 					= $json_data->data[0]->arrows_broken ; 
				$apikey 						= $json_data->data[0]->apikey ; 
					
				$playerLevel = new PlayerLevelStatus();
				
				$dataArr['user_id']					=	$user_id;
				$dataArr['levelwin']				=	$levelwin;
				
				$dataArr['region']					=	$region;
				$dataArr['hunt_type']				= 	$hunt_type;				
				$dataArr['progression_hunt_num']	=	$progression_hunt_num;								
				$dataArr['cash_hunt_num'] 			= 	$cash_hunt_num;
				$dataArr['invitational_hunt_num'] 	= 	$invitational_hunt_num ; 
				$dataArr['premium_currency_won'] 	= 	$premium_currency_won ; 
				$dataArr['normal_currency_won'] 	= 	$normal_currency_won ; 
				$dataArr['xp_earned'] 				= 	$xp_earned ; 
				$dataArr['largest_kill'] 			= 	$largest_kill ; 
				$dataArr['longest_kill'] 			= 	$longest_kill ; 
				$dataArr['valid_kills'] 			= 	$valid_kills ; 
				$dataArr['arrows_fired'] 			= 	$arrows_fired ; 
				$dataArr['arrows_broken'] 			= 	$arrows_broken ; 
				
				if( ( empty($apikey) ) ||  ($apikey != $this->restApiKey) ){
					return new JsonModel(array( 'error' => true,'message' => "Authentication failure ! Invalid API-KEY ." ));
					
				} else {						
										
						$playerLevel->exchangeArray($dataArr);						
						$id =  $this->getPlayerLevelStatusTable()->savePlayerLevelStatus($player);
				}
				
				
				if(!empty($id) && $id > 0 )
				{
					$dataVal = $this->getPlayerLevelStatusTable()->getPlayerLevelStatus($id);									
					return new JsonModel( array('data' => $dataVal ) );
				}
				else
				{							
					return new JsonModel(array( 'error' => true, 'message' => "Duplicate Entry of Social-ID #$socialId" ));
				}								

					
			} else { 

					$errorJson = array('message'   => "Invalid Json Format!",);		
					return new JsonModel(array('errors' => array($errorJson)));								
			
			}
			
			exit();

		}


		public function deleteAction()
		{
			# code...	
			$id = (int) $this->params()->fromRoute('id', 0);			
			$this->getBankTable()->deleteBank($id);
			return new JsonModel(array(	'data' => 'deleted',));
		}

	

}

