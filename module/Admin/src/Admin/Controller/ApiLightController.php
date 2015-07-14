<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;



use Zend\View\Model\ViewModel;
use Admin\Model\Light;          // <-- Add this import
use Admin\Form\LightForm;       // <-- Add this import
use Admin\Model\LightTable; 
use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;



class ApiLightController extends AbstractRestfulController
{
	
		protected $lightTable;
		protected $restApiKey = "AXDFG65432@789GAME#987";
		
		public function getLightTable()
		{
			if (!$this->lightTable) {
				$sm = $this->getServiceLocator();
				$this->lightTable = $sm->get('Admin\Model\LightTable');
			}
			return $this->lightTable;
		}

		
		
		
		
		public function getList()
		{
				# code...
				//$response = array();
				$results 	= $this->getLightTable()->fetchAll();
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
					//$response["error"] = 3;
					//$response["error_msg"] = "Could not find rows";
					return new JsonModel(array( 'error' => true,'message' => "No Data Found" ));
				}
				

				
		}

		public function get($id)
		{
			# code...
			//$response = array();
			$id = (int) $id;
			$dataVal = $this->getLightTable()->getLight($id);
			
			if(!$dataVal)
			{				
				//$response["error"] = 2;
				//$response["error_msg"] = "Could not find row $id";
				return new JsonModel(array( 'error' => true,'message' => "Content ID $id does not exist " ));
			}	
			else
			{	
				//$response["success"] = 1;					
				return new JsonModel(array( 'data' => $dataVal));
			}

	
		}

		public function create($data)
		{
				#code...
				/*
				return new JsonModel(array(
									'data' => 'Created !',
				));
				*/
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

