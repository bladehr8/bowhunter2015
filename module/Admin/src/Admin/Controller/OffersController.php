<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Offers;          // <-- Add this import
use Admin\Form\OffersForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class OffersController extends AbstractActionController
{
	
	protected $offersTable;
	public $thumbWidth = 300;
	public $thumbHeight = 300;
	
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
        if (!$this->offersTable) {
            $sm = $this->getServiceLocator();
            $this->offersTable = $sm->get('Admin\Model\OffersTable');
        }
        return $this->offersTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//$servoceLocator = $this->getServiceLocator();
		//$config         = $this->getServiceLocator()->get('config');
		//$myValue        = $config['offers_thumb_img'];
		
		//echo $myValue['thumbWidth'] ; exit;

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
		$form = new OffersForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$offers = new Offers();
				//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
				//$screenshots->setDbAdapter($dbAdapter);
				
				
				
				
				
				
				
				
				$form->setInputFilter($offers->getInputFilter());
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
						$originalFileName = 'offers-'.time().'.'.$ext;
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
						move_uploaded_file($data['field_image']['tmp_name'], 'public/upload/offers/'.$originalFileName );
						$imagePath   = 'public/upload/offers/'.$originalFileName ;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 



					$dataArr['title']						=	$request->getPost('title') ; 
					$dataArr['short_note']					=	$request->getPost('short_note');	
					$dataArr['external_link']				=	$request->getPost('external_link');						
					
					$dataArr['created_on'] 					=	(new \DateTime())->format('Y-m-d H:i:s');
					$dataArr['active']						= 	$request->getPost('active'); 	

					$offers->exchangeArray($dataArr);
					$this->getTable()->saveData($offers);			
					return $this->redirect()->toRoute('offers', array('action'=>'index'));

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('offers', array('action'=>'add'));
		 }
		 
		$getData = $this->getTable()->getData($id);	
		$form = new OffersForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$form->setDbAdapter($dbAdapter);	
		
		
		
		
		
        if ($request->isPost()) {	
		
			
			$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
			$offers = new Offers();
			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			//$pages->setDbAdapter($dbAdapter);  
			$form->setInputFilter($offers->getInputFilter());
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
						
						$originalFileName = 'offers-'.time().'.'.$ext;
						$dataArr['field_image']			=	$originalFileName;
						move_uploaded_file( $data->field_image['tmp_name'] , 'public/upload/offers/'.$originalFileName );
						$imagePath   = 'public/upload/offers/'.$originalFileName;
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 


					$dataArr['id']							=	$data->id; 
					$dataArr['title']						=	$data->title ; 
					$dataArr['short_note']					=	$data->short_note;
					$dataArr['external_link']				=	$data->external_link;
					$dataArr['created_on'] 					=	date("Y-m-d H:i:s") ;
					$dataArr['active']						= 	$data->active; 	

					$offers->exchangeArray($dataArr);
					$this->getTable()->saveData($offers);			
					return $this->redirect()->toRoute('offers', array('action'=>'index'));

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
		return $this->redirect()->toRoute('offers' , array('action' => 'index'));
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

