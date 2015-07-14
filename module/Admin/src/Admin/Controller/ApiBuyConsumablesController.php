<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\ViewModel;



use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;

//class ApiPlayerController extends AbstractRestfulController

class ApiBuyConsumablesController extends AbstractActionController
{
	
		
		protected $restApiKey = "AXDFG65432"; // Static/fixed restApiKey




		public function createAction()
		{						
			$response = array();
			$jsondata = $this->params()->fromQuery('json');
			// translate UTF8 to English characters
			$data = iconv('UTF-8', 'ASCII//TRANSLIT', $jsondata);
			$json_data  = json_decode($data );

			if($json_data) { 

				$user_id 					= $json_data->data[0]->id ; 
				$type 						= $json_data->data[0]->type;
				$premium_cost 				= $json_data->data[0]->premium_cost; 
				$normal_cost 				= $json_data->data[0]->normal_cost ; 
				//$weapon_consumable_id 		= $json_data->data[0]->weapon_consumable_id ; 
				$weapon_consumable_id		= 0 ;
				$apikey 					= $json_data->data[0]->apikey ; 
		
				
				$dataArr['player_id']				=	(int)$user_id;
				$dataArr['weapon_consumable_id']	=	$weapon_consumable_id ;
				$dataArr['name']					=	trim($type);
				$dataArr['premium_cost']			= 	$premium_cost ;			
				$dataArr['normal_cost']				=	$normal_cost;				
				$dataArr['created_on'] 				= 	time();
				$dataArr['active'] 					= 	1; 
				

				
				if( ( empty($apikey) ) ||  ($apikey != $this->restApiKey) ){
					return new JsonModel(array( 'error' => true,'message' => "Authentication failure ! Invalid API-KEY ." ));
					
				} else {				

						$return_id =  $this->saveConsumables($dataArr);
						//echo 'HHH' . $return_id;
						if(!empty($return_id) && $return_id > 0 )																	
							return new JsonModel(array( 'error' => false, 'message' => "Success" ));			
						else						
							return new JsonModel(array( 'error' => true,  ));						
				}
	
			} else { 

					$errorJson = array('message'   => "Invalid Json Format!",);		
					return new JsonModel(array('errors' => array($errorJson)));								
			
			}
			
			exit();

		}


		public function saveConsumables($data)
		{
				$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
				$sql       = new Sql($dbAdapter);
				$insert    = $sql->insert('weapon_consumables_has_players');
				$insert->values($data);
				$statement = $sql->prepareStatementForSqlObject($insert);				
				$results    = $statement->execute();			
				$id = $dbAdapter->getDriver()->getLastGeneratedValue();
				//print 'I am here !' . $id; exit();
				return $id;	
				
		}		
		
		
		
		
		

		public function deleteAction()
		{
			# code...	
			$id = (int) $this->params()->fromRoute('id', 0);			
			$this->getBankTable()->deleteBank($id);
			return new JsonModel(array(	'data' => 'deleted',));
		}

	

}

