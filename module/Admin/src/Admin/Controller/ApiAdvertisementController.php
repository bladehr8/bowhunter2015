<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Advertisement;          // <-- Add this import
use Admin\Form\AdvertisementForm;       // <-- Add this import



use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;



//class ApiAdvertisementController extends AbstractRestfulController
class ApiAdvertisementController extends AbstractActionController
{
	
		protected $advertisementTable;
		protected $restApiKey = "AXDFG65432";
		
		public function getTable()
		{
			if (!$this->advertisementTable) {
				$sm = $this->getServiceLocator();
				$this->advertisementTable = $sm->get('Admin\Model\AdvertisementTable');
			}
			return $this->advertisementTable;
		}

		
		public function listAction()
		{
			# Get Player Details byID
			$response = array();
			//$id = (int) $id;
			$id = (int) $this->params()->fromRoute('id', 0);
			if( $id > 0)
			{	
				$dataVal = $this->getTable()->getData($id);				
				if(!$dataVal)
				{				
					$errorJson = array('message'   => "failure",);
					return new JsonModel(array( 'error' => true,'message' => "List ID $id does not exist " ));
				}	
				else
				{	
					//$response["success"] = 1;					
					return new JsonModel(array( 'data' => $dataVal));
				}
							
			}
			else
			{

				$results 	= $this->getTable()->fetchAll();
				$data 		= array();
				foreach($results as $result) {
					$data[] = $result;
				}

				if(count($data) >0 )
				{
					return new JsonModel(array( 'data' => $data ));
				}
				else
				{
					return new JsonModel(array( 'error' => true,'message' => "No Data Found" ));
				}
		
				
			}
			
			
			
			
				exit();

		}











		
		
		/*
		public function getList()
		{

				$results 	= $this->getTable()->fetchAll();
				$data 		= array();
				foreach($results as $result) {
					$data[] = $result;
				}

				
				if(count($data) >0 )
				{
					return new JsonModel(array( 'data' => $data ));
				}
				else
				{
					$errorJson = array('message'   => "empty",);		
					return new JsonModel(array('errors' => array($errorJson)));	

				}
				

				
		}

		public function get($id)
		{

			$id = (int) $id;
			$dataVal = $this->getTable()->getData($id);
			
			if(!$dataVal)
			{				
				$errorJson = array('message'   => "failure",);
				return new JsonModel(array('errors' => array($errorJson)));	
			}	
			else
			{	
				//$response["success"] = 1;					
				return new JsonModel(array( 'data' => $dataVal));
			}

	
		}
*/
		public function createAction()
		{
			$response = array();
			$clickCount = 0;
			$viewCount = 0;
			$jsondata = $this->params()->fromQuery('json');
			// translate UTF8 to English characters
			$data = iconv('UTF-8', 'ASCII//TRANSLIT', $jsondata);
			$json_data  = json_decode($data );

			if($json_data) { 

			$userID 				= $json_data->data[0]->user_id ; 
			$advertisementID 		= $json_data->data[0]->advertisement_id;
			$click 					= $json_data->data[0]->click; 
			$status 				= $json_data->data[0]->status ; 					
			$apikey 				= $json_data->data[0]->apikey ; 


			$userID  				= (int) $userID ;
			$advertisementID  		= (int) $advertisementID ;
			$status  				= strtolower($status);

			if( $click ) { 
				$clickCount = $clickCount + 1;
				$viewCount = $viewCount + 1;
			}
			else { $viewCount = $viewCount + 1; }
			

			$dataArr['user_id']			=	$userID;
			$dataArr['add_id']			=	$advertisementID;							
			$dataArr['click_count']		=	$clickCount ;
			$dataArr['view_count']		= 	$viewCount;		
			$dataArr['date'] 			= 	date("Y-m-d");
			$dataArr['status'] 			= 	$status ; 


			if( ( empty($apikey) ) ||  ($apikey != $this->restApiKey) ){
			
					$errorJson = array('message'   => "Authentication failure ! Invalid API-KEY .",);
					return new JsonModel(array('errors' => array($errorJson)));	
					exit();
			} 
			else {									
					$affectedRow =  $this->getTable()->saveAdvertisementLogs($dataArr);
			}

			if($affectedRow)
			{	
				$errorJson = array('message'   => "Success");
				return new JsonModel( array('data' => array($errorJson)) );
			}
			else
			{		
				$errorJson = array('message'   => "Duplicate Entry");					
				return new JsonModel( array('errors' => array($errorJson)) );
			}								


			} 
			else
			{ 
				$errorJson = array('message'   => "Invalid Json!",);		
				return new JsonModel(array('errors' => array($errorJson)));	
				exit();
			}						
	
		}

		public function update($id, $data)
		{
				/*
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

			*/
		}

		public function delete($id)
		{
			# code...
			/*	
			$this->getBankTable()->deleteBank($id);

			return new JsonModel(array(
						'data' => 'deleted',
						));
				
			*/	
			
			
		}

	

}

