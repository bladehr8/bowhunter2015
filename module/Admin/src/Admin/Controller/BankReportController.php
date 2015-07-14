<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\AdReport;          // <-- Add this import
//use Admin\Form\AdvertisementForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class BankReportController extends AbstractActionController
{
	
	protected $paymentHistory;

	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }

	public function getTable()
    {
        if (!$this->paymentHistory) {
            $sm = $this->getServiceLocator();
            $this->paymentHistory = $sm->get('Admin\Model\BankReportTable');
        }
        return $this->paymentHistory;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		$request = $this->getRequest();
        if ($request->isPost())
		{
			$paymentStatus 		= $request->getPost('payment_status');
			$daterange 			= $request->getPost('daterange');
			
			$paymentStatus     	= (!empty($paymentStatus)) ? $paymentStatus : null;
			$daterange     		= (!empty($daterange )) ? $daterange  : null;

			$results 			= $this->getTable()->fetchAll($paymentStatus , $daterange)	;		

		}
		else
		{
			$results = $this->getTable()->fetchAll();
			$daterange = null ; 
		}
		
		
		
		
		
		
		
		
		return new ViewModel( array('rows' => $results, 'flashMessages'  => $this->flashmessenger()->getMessages(), 'daterange' => $daterange , ));			

	}
	

	
	public function deleteAction()
	{
		$this->init();	
		if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if($id) { 
		$this->getTable()->deleteData($id);
		return $this->redirect()->toRoute('advertisement' , array('action' => 'index'));
		}
		
		
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel( array('bankoptions' => $this->getRegionTable()->fetchRowsById($id) ,)
		);
		
	}
	
	
	

	
	

}

