<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\ScreenShots;          // <-- Add this import
use Admin\Form\ScreenShotsForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class ScreenShotsController extends AbstractActionController
{
	
	protected $screenShotsTable;
	public $thumbWidth = 208;
	public $thumbHeight = 168;
	
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
        if (!$this->screenShotsTable) {
            $sm = $this->getServiceLocator();
            $this->screenShotsTable = $sm->get('Admin\Model\ScreenShotsTable');
        }
        return $this->screenShotsTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
	
	//$renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
	//echo  $renderer->basePath();
	//exit;
	//$date = Zend_Date::now();
	//define('CONST_SERVER_TIMEZONE', 'UTC');
	//echo (new \DateTime())->format('Y-m-d H:i:s');

		$this->init();
		$this->appendMyJs();
		$form = new ScreenShotsForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$screenshots = new ScreenShots();
				$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
				$screenshots->setDbAdapter($dbAdapter);
				
				
				
				
				
				
				
				
				$form->setInputFilter($screenshots->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','JPEG','jpeg','png');

					if (!empty($data['field_image'])) {
						ErrorHandler::start();
						$originalFileName = trim($data['field_image']['name']);						
						$efilename = explode('.', $originalFileName);						
						$ext = end( $efilename );
						
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$originalFileName = 'screen-shots-'.time().'.'.$ext;
						//$reNameFile = 
						/*
							$filter = new \Zend\Filter\File\Rename(array(
																			"target"    => "public/upload/img.jpeg",
																			"randomize" => true,
																	));
																	*/
							
							//print_r( $filter->filter($data['field_image']) );
						//exit();
						
						
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file($data['field_image']['tmp_name'], 'public/upload/screen-shots/'.$originalFileName );
						$imagePath   = 'public/upload/screen-shots/'.$originalFileName ;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbWidth , $this->thumbHeight);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 



					$dataArr['title']						=	$request->getPost('title') ; 
					$dataArr['short_note']					=	$request->getPost('short_note');	
					//$dataArr['content']						=	$request->getPost('content');						
					
					$dataArr['created_on'] 					=	(new \DateTime())->format('Y-m-d');
					$dataArr['active']						= 	$request->getPost('active'); 	

					$screenshots->exchangeArray($dataArr);
					$this->getTable()->saveData($screenshots);			
					return $this->redirect()->toRoute('screen-shots', array('action'=>'index'));

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
		$form = new ScreenShotsForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$form->setDbAdapter($dbAdapter);	
		
		
		
		
		
        if ($request->isPost()) {	
		
			
			$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
			$screenShots = new ScreenShots();
			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			//$pages->setDbAdapter($dbAdapter);  
			$form->setInputFilter($screenShots->getInputFilter());
			$form->setData($post);
			

			   if ($form->isValid())
			   {
					$data = $form->getData();
					//echo '<pre>'; print_r($data); exit;
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','jpe','jpeg','png');

					if (!empty($data->field_image['name'])) {					
						ErrorHandler::start();
						$originalFileName = trim($data->field_image['name']);					
						$efilename = explode('.', $originalFileName);					
						$ext = end( $efilename );					
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						
						$originalFileName = 'screen-shots-'.time().'.'.$ext;
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file( $data->field_image['tmp_name'] , 'public/upload/screen-shots/'.$originalFileName );
						$imagePath   = 'public/upload/screen-shots/'.$originalFileName;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbWidth , $this->thumbHeight );
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 


					$dataArr['id']							=	$request->getPost('id') ; 
					$dataArr['title']						=	$request->getPost('title') ; 
					$dataArr['short_note']					=	$request->getPost('short_note');
					$dataArr['created_on'] 					=	date("Y-m-d H:i:s") ;
					$dataArr['active']						= 	$request->getPost('active'); 	

					$screenShots->exchangeArray($dataArr);
					$this->getTable()->saveData($screenShots);			
					return $this->redirect()->toRoute('screen-shots', array('action'=>'index'));

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
		return $this->redirect()->toRoute('screen-shots' , array('action' => 'index'));
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

