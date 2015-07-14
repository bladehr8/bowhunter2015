<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\BankReport;             // <-- Add this import
use Admin\Model\BankReportTable; 

use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;

//class ApiPlayerController extends AbstractRestfulController

class ApiBankReportController extends AbstractActionController
{
	
		protected $paymentHistory;
		protected $restApiKey = "AXDFG65432"; // Static/fixed restApiKey

		
		public function getBankReportTable()
		{
			if (!$this->paymentHistory) {
				$sm = $this->getServiceLocator();
				$this->paymentHistory = $sm->get('Admin\Model\BankReportTable');
			}
			return $this->paymentHistory;
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
				$pack_id 						= $json_data->data[0]->pack_id;
				$pack_name 						= $json_data->data[0]->pack_name; 
				$price 							= $json_data->data[0]->price ; 
				$bucks 							= $json_data->data[0]->bucks ; 
				$gold_coins 					= $json_data->data[0]->gold_coins ; 
				$transcation_id 				= $json_data->data[0]->transcation_id ; 
				$status 						= $json_data->data[0]->status ; 
				$apikey 						= $json_data->data[0]->apikey ; 
					
				$bankReport = new BankReport();
				
				$dataArr['player_id']				=	$user_id;
				$dataArr['bank_id']					=	$pack_id;
				
				$dataArr['pay_amount']					=	$price;
				$dataArr['product_name']				= 	$pack_name ;			
				$dataArr['transaction_id']				=	$transcation_id;							
				$dataArr['bucks'] 						= 	$bucks;
				$dataArr['gold_coins'] 					= 	$gold_coins ; 
				$dataArr['created_on'] 					= 	time();
				$dataArr['status'] 						= 	trim($status); 
				

				
				if( ( empty($apikey) ) ||  ($apikey != $this->restApiKey) ){
					return new JsonModel(array( 'error' => true,'message' => "Authentication failure ! Invalid API-KEY ." ));
					
				} else {						
										
						$bankReport->exchangeArray($dataArr);						
						$return_id =  $this->getBankReportTable()->saveData($bankReport);
				}
				//echo 'ReturnId #'.$return_id; exit;
				
				if(!empty($return_id) && $return_id > 0 )
				{
					$dataVal = $this->getBankReportTable()->getData($return_id);									
					return new JsonModel( array('data' => $dataVal ) );
				}
				else
				{							
					return new JsonModel(array( 'error' => true,  ));
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

