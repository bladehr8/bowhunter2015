<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\News;          // <-- Add this import
use Admin\Form\NewsForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class NewsController extends AbstractActionController
{
	
	protected $newsTable;

	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function appendMyJs()
	{                 
		$this->getViewHelper('inlineScript')->appendScript('jQuery(document).ready(function() {  $("textarea.ckeditor").ckeditor(); });',	        
	        'text/javascript', array('noescape' => true));
	}

	protected function getViewHelper($helperName)
	{
		return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
	}


	
	public function getTable()
    {
        if (!$this->newsTable) {   $this->newsTable = $this->getServiceLocator()->get('Admin\Model\NewsTable');        }           
        return $this->newsTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{	

		$this->init();
		$this->appendMyJs();
		$form = new NewsForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post = $request->getPost()->toArray() ;
				$news = new News();
				$form->setInputFilter($news->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();

					$dataArr['title']						=	$data['title'] ; 
					$dataArr['external_url']				=	$request->getPost('external_url');	
					$dataArr['news_content']				=	$request->getPost('news_content');						
					$dataArr['modified_on'] 				=	time() ;
					$dataArr['created_on'] 					=	time() ;
					$dataArr['active']						= 	$request->getPost('active'); 

					$validator = $this->getTable()->isExistName()	;
					if ($validator->isValid($data['title'])) {						
						$this->flashMessenger()->addMessage('News title is already defined !');
						return $this->redirect()->toRoute('news', array('action'=>'add'));						
					} else {
								$news->exchangeArray($dataArr);
								$this->getTable()->saveData($news);		
								$this->flashMessenger()->addMessage('Records has been saved successfully.');		
								return $this->redirect()->toRoute('news', array('action'=>'index'));
					
					
						// username is invalid; print the reason
						//$messages = $validator->getMessages();
						//foreach ($messages as $message) {
							//echo "$message\n";
						//}
					}					
					//exit;
	
					

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('pages', array('action'=>'add'));
		 }
		 
		$getData = $this->getTable()->getData($id);	
		$form = new NewsForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$form->setDbAdapter($dbAdapter);	
		
		
		
		
		
        if ($request->isPost()) {	
		
			
			//$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
			$post = $request->getPost()->toArray() ;
			$news = new News();
			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			//$pages->setDbAdapter($dbAdapter);  
			$form->setInputFilter($news->getInputFilter());
			$form->setData($post);
			

			   if ($form->isValid())
			   {
					$data = $form->getData();
					//echo '<pre>'; print_r($data); exit;
					$isValid = true; 

					$dataArr['id']							=	$request->getPost('id') ; 	
					
					$dataArr['title']						=	$request->getPost('title') ; 
					$dataArr['external_url']				=	$request->getPost('external_url');	
					$dataArr['news_content']				=	$request->getPost('news_content');						
					$dataArr['modified_on'] 				=	time() ;
					$dataArr['created_on'] 					=	time() ;
					$dataArr['active']						= 	$request->getPost('active'); 					
					
					
					
					
					
					
					
					
					

					$news->exchangeArray($dataArr);
					$this->getTable()->saveData($news);			
					return $this->redirect()->toRoute('news', array('action'=>'index'));

			 }			
			
			
			
			
			
			
			
			
			


		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
		 'getData' => $getData,
     );
	

	
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
		$this->flashMessenger()->addMessage('Record has been deleted !');
		return $this->redirect()->toRoute('news' , array('action' => 'index'));
		}
		
		
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		//echo '<pre>'; print_r($this->getTable()->getData($id)); exit;
		return new ViewModel( array('row' => $this->getTable()->getData($id) ,)
		);
		
	}
	
	
	

	
	

}

