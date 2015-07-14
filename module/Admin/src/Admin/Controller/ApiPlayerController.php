<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Player;          // <-- Add this import
use Admin\Form\PlayerForm;       // <-- Add this import
use Admin\Model\PlayerTable; 

use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;

//class ApiPlayerController extends AbstractRestfulController

class ApiPlayerController extends AbstractActionController
{
	
		protected $playerTable;	
		protected $restApiKey = "AXDFG65432";

		
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

		public function listAction()
		{
			# Get Player Details byID
			$response = array();
			//$id = (int) $id;
			$id = (int) $this->params()->fromRoute('id', 0);
			if( $id > 0)
			{
			
					$dataVal = $this->getPlayerTable()->getPlayer($id);			
					if(!$dataVal)
					{				
						return new JsonModel(array( 'error' => true,'message' => "Player ID $id does not exist " ));
					}	
					else
					{			
						return new JsonModel(array( 'data' => $dataVal ));
					}
			
			
					exit();
			
			}
			else
			{
				
				
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
		
				exit();
			}
			
			
			
			
				exit();

		}

		public function createAction()
		{
						#code...
						$response = array();
						$jsondata = $this->params()->fromQuery('json');
						// translate UTF8 to English characters
						$data = iconv('UTF-8', 'ASCII//TRANSLIT', $jsondata);
						$json_data  = json_decode($data );

						if($json_data) { 

								$name 		= $json_data->data[0]->name ; 
								$email 		= $json_data->data[0]->email;
								$gender 	= $json_data->data[0]->gender; 
								$socialType = $json_data->data[0]->socialType ; 
								$socialId 	= $json_data->data[0]->socialId ; 
								$apikey 	= $json_data->data[0]->apikey ; 
	
								
						} else { 

								$errorJson = array('message'   => "Invalid Json!",);		
								return new JsonModel(array('errors' => array($errorJson)));	
								exit();
						
						}

							$player = new Player();
							$dataArr['name']		=	$name;
							$dataArr['email']		=	$email;
							
							$dataArr['gender']		=	$gender;
							$dataArr['socialType']	= 	$socialType;				
							$dataArr['socialId']	=	$socialId;								
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
							elseif(empty($socialId) || $socialId <= 0 )
							{
								//$response["error"] = 2;	
								//$response["error_msg"] = "Invalid  Social-ID  $socailId";								
								return new JsonModel(array( 'error' => true, 'message' => "Invalid  Social-ID  $socialId" ));
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
								return new JsonModel(array( 'error' => true, 'message' => "Duplicate Entry of Social-ID #$socialId" ));
							}
	
						//}

					

		}

		# Update Player Info Details
		
		
		public function updateAction()
		{
				
						#code...
						$response = array();
						//$request = $this->getRequest();
						$jsondata = $this->params()->fromQuery('json');
						// translate UTF8 to English characters
						$data = iconv('UTF-8', 'ASCII//TRANSLIT', $jsondata);
						$json_data  = json_decode($data );
						
						
						//$json_data  = json_decode($jsondata);

						if( count ( $json_data->data) <= 0 )  {
								$errorJson = array('message'   => "error",);		
								return new JsonModel(array('errors' => array($errorJson)));	
								exit();
						} else { 

									$id 					=(int) $json_data->data[0]->id ; 
									$xpPoint 				= $json_data->data[0]->xpPoint;
									$totalPremiumCurrency 	= $json_data->data[0]->totalPremiumCurrency; 
									$totalNormalCurrency 	= $json_data->data[0]->totalNormalCurrency ; 
									$totalEnergy 			= $json_data->data[0]->totalEnergy ; 
									$current_region 		= $json_data->data[0]->current_region ; 
									
									$progressionHuntNum 	= $json_data->data[0]->progressionHuntNum ;
									$cashHuntNum 			= $json_data->data[0]->cashHuntNum ; 
									$invitationalHuntNum 	= $json_data->data[0]->invitationalHuntNum ; 
									
									$bowEquipped 			= $json_data->data[0]->bowEquipped ; 
									$arrowEquipped 			= $json_data->data[0]->arrowEquipped ; 
									$sightEquipped 			= $json_data->data[0]->sightEquipped ; 
									$stabilizerEquipped 	= $json_data->data[0]->stabilizerEquipped ; 
									$quiverEquipped 		= $json_data->data[0]->quiverEquipped ; 
									$opticsEquipped 		= $json_data->data[0]->opticsEquipped ; 
									
									$dressPickId 			= $json_data->data[0]->dressPickId ; 
									$hairColorId 			= $json_data->data[0]->hairColorId ; 
									$skinColorId 			= $json_data->data[0]->skinColorId ; 
									$faceColorId 			= $json_data->data[0]->faceColorId ; 
									
									$thermalBatteryCount 		= $json_data->data[0]->thermalBatteryCount ; 
									$infraredBatteryCount 		= $json_data->data[0]->infraredBatteryCount ; 
									$rangefinderBatteryCount 	= $json_data->data[0]->rangefinderBatteryCount ; 
									$saltlickCount 				= $json_data->data[0]->saltlickCount ; 
									$cornCount 					= $json_data->data[0]->cornCount ; 

									$apikey 					= $json_data->data[0]->apikey ; 
						
						}

							$player = new Player();
							$dataArr['id']		=	$id;
							
							$dataArr['cornCount']					=	$cornCount;
							$dataArr['saltlickCount']				=	$saltlickCount;
							$dataArr['rangefinderBatteryCount']		=	$rangefinderBatteryCount;
							$dataArr['infraredBatteryCount']		= 	$infraredBatteryCount;				
							$dataArr['thermalBatteryCount']			=	$thermalBatteryCount;
							
							$dataArr['progressionHuntNum']			=	$progressionHuntNum;
							$dataArr['cashHuntNum']			=	$cashHuntNum;
							$dataArr['invitationalHuntNum']			=	$invitationalHuntNum;


							
							$dataArr['faceColorId']			=	$faceColorId;	
							$dataArr['skinColorId']			=	$skinColorId;	
							$dataArr['hairColorId']			=	$hairColorId;	
							$dataArr['dressPickId']			=	$dressPickId;	
							
							
							$dataArr['bowEquipped']			=	$bowEquipped;	
							$dataArr['arrowEquipped']		=	$arrowEquipped;	
							$dataArr['sightEquipped']		=	$sightEquipped;	
							$dataArr['stabilizerEquipped']	=	$stabilizerEquipped;	
							$dataArr['quiverEquipped']		=	$quiverEquipped;	
							$dataArr['opticsEquipped']		=	$opticsEquipped;	


							
							$dataArr['xpPoint'] 				= $xpPoint ; 
							$dataArr['totalNormalCurrency'] 	= $totalNormalCurrency; 
							$dataArr['totalPremiumCurrency'] 	= $totalPremiumCurrency;
							$dataArr['totalEnergy'] 			= $totalEnergy;
							$dataArr['current_region'] 			= $current_region;
							
							if( ( empty($apikey) ) ||  ($apikey != $this->restApiKey) ){
							
									$errorJson = array('message'   => "Authentication failure ! Invalid API-KEY .",);		
									return new JsonModel(array('errors' => array($errorJson)));	
									exit();
									
							} else {					
									$player->exchangeArray($dataArr);						
									$this->getPlayerTable()->updatePlayer($player);
									$errorJson = array('message'   => "TRUE",);		
									return new JsonModel(array('success' => array($errorJson)));	
							}
							
							
							//if(!empty($id) && $id > 0 )
							//{
								//$dataVal = $this->getPlayerTable()->getPlayer($id);									
								//return new JsonModel( array('data' => $dataVal ) );
							//}
							//else
							//{							
								//return new JsonModel(array( 'error' => true, 'message' => "Duplicate Entry of Social-ID #$socialId" ));
							//}

			
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

