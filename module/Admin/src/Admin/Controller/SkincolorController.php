<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Skincolor;          // <-- Add this import
use Admin\Form\SkincolorForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;



class SkincolorController extends AbstractActionController
{
	
	protected $skincolorTable;
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getSkincolorTable()
    {
        if (!$this->skincolorTable) {
            $sm = $this->getServiceLocator();
            $this->skincolorTable = $sm->get('Admin\Model\SkincolorTable');
        }
        return $this->skincolorTable;
    }

	
	public function addAction()
	{
		$this->init();
		$form = new SkincolorForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
	
            $skincolor = new Skincolor();
            $form->setInputFilter($skincolor->getInputFilter());
            $form->setData($request->getPost());

           if ($form->isValid()) {							

				$name 	=  $request->getPost('name') ; 
				$active 	=  $request->getPost('active'); 
				
				$dataArr['name']=$name;
				$dataArr['active']=1;
				
				
				$skincolor->exchangeArray($dataArr);
                $this->getSkincolorTable()->saveSkincolor($skincolor);
			
			     return $this->redirect()->toRoute('skincolor', array('action'=>'index'));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());	
	
	}
	
    public function indexAction()
    { 
	
		$this->init();
	
		 return new ViewModel(array(
				'skincolors' => $this->getSkincolorTable()->fetchAll(),
			));
		
	}
	
	public function deleteAction()
	{
		$this->init();
		
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$this->getSkincolorTable()->deleteSkincolor($id);
	}
	
	
	public function detailsAction()
	{
	
		$this->init();
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(     array(
								'skincolorDetails' => $this->getSkincolorTable()->getSkincolor($id) ,
								)
		);
		
		
		
	}
	
	
	

	
	

}

