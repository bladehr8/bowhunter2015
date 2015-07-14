<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;

use Admin\Model\ShorthandTarget;          
use Admin\Form\ShorthandTargetForm;       
use Admin\Model\ShorthandTargetTable; 



use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



use Zend\View\Model\JsonModel;



class ApiShortTargetController extends AbstractRestfulController
{
	
		protected $shortTargetTable;
		protected $restApiKey = "AXDFG65432@789GAME#987";
		
		public function getTable()
		{
			if (!$this->shortTargetTable) {
				$sm = $this->getServiceLocator();
				$this->shortTargetTable = $sm->get('Admin\Model\ShorthandTargetTable');
			}
			return $this->shortTargetTable;
		}

		
		
		
		
		public function getList()
		{
				# code...
				//$response = array();
				$results 	= $this->getTable()->fetchAll();
				$data 		= array();
				foreach($results as $result) {
					$data[] = $result;
				}

				//return array('data' => $data);
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
			$dataVal = $this->getTable()->getShorthandTarget($id);
			
			if(!$dataVal)
			{			
				$errorJson = array('message'   => "failure",);
				return new JsonModel(array('errors' => array($errorJson)));	

			}	
			else
			{		
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

