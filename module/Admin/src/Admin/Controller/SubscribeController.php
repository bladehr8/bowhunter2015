<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Subscribe;          // <-- Add this import
//use Admin\Form\PagesForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class SubscribeController extends AbstractActionController
{
	
	protected $subscribeTable;

	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }

	
	public function getTable()
    {
        if (!$this->subscribeTable) {
            $sm = $this->getServiceLocator();
            $this->subscribeTable = $sm->get('Admin\Model\SubscribeTable');
        }
        return $this->subscribeTable;
    }
	

	public function indexAction()
	{ 
		$this->init();	
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

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
		return $this->redirect()->toRoute('subscribe' , array('action' => 'index'));
		}
		
		
	}	
	


}

