<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\Advertisement;          // <-- Add this import
use Admin\Form\AdvertisementForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class AdvertisementController extends AbstractActionController
{
	
	protected $advertisementTable;
	public $thumbWidth = 100;
	public $thumbHeight = 100;
	public $filePath = '/home/redappletech2010/public_html/matrixmedia/huntinggame/public/upload/'; 
	public $rootDomain = 'http://matrixmedia.redappletech.com';
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function appendMyJs()
	{                 
		$this->getViewHelper('HeadScript')->appendFile('/huntinggame/public/assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js');
		$this->getViewHelper('HeadScript')->appendFile('/huntinggame/public/assets/plugins/autosize/jquery.autosize.min.js');  
		$this->getViewHelper('HeadScript')->appendFile('/huntinggame/public/assets/plugins/select2/select2.min.js');  
		$this->getViewHelper('HeadScript')->appendFile('/huntinggame/public/assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js'); 

		$this->getViewHelper('HeadScript')->appendFile( '/huntinggame/public/assets/plugins/jquery-maskmoney/jquery.maskMoney.js');  
		$this->getViewHelper('HeadScript')->appendFile( '/huntinggame/public/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js');  
		$this->getViewHelper('HeadScript')->appendFile('/huntinggame/public/assets/plugins/ckeditor/adapters/jquery.js');  
		$this->getViewHelper('HeadScript')->appendFile('/huntinggame/public/assets/js/form-elements.js');  
		

	     
		
		$this->getViewHelper('inlineScript')->appendScript('jQuery(document).ready(function() { FormElements.init(); });',	        
	        'text/javascript', array('noescape' => true));
		
		
		

	}

	protected function getViewHelper($helperName)
	{
		return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
	}

	
	
	
	
	
	
	
	
	
	
	public function getTable()
    {
        if (!$this->advertisementTable) {
            $sm = $this->getServiceLocator();
            $this->advertisementTable = $sm->get('Admin\Model\AdvertisementTable');
        }
        return $this->advertisementTable;
    }
	
	public function indexAction()
	{ 
		$this->init();	
		//echo '<pre>'; print_r($this->getRegionTable()->fetchAll()); exit;
		return new ViewModel( array('rows' => $this->getTable()->fetchAll(), 'flashMessages'  => $this->flashmessenger()->getMessages(), ));			

	}
	
	
	
	

	
	public function addAction()	{
	
	$renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
	//echo  $renderer->basePath();
	//exit;
		
		$this->init();
		$this->appendMyJs();
		$form = new AdvertisementForm();
        $form->get('submit')->setValue('Submit');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
				// Make certain to merge the files info!
				$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
				$advertise = new Advertisement();
				$form->setInputFilter($advertise->getInputFilter());
				$form->setData($post);

			   if ($form->isValid())
			   {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					//echo $_SERVER['DOCUMENT_ROOT'];
					//exit;

					if (!empty($data['image_1'])) {
						ErrorHandler::start();
						$originalFileName = trim($data['image_1']['name']);
						$dataArr['image_1']			=	$this->rootDomain . $renderer->basePath().'/upload/thumb/'.$originalFileName;
						move_uploaded_file($data['image_1']['tmp_name'], $this->filePath.$data['image_1']['name']);
						$imagePath   = $this->filePath . $data['image_1']['name'];
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save( $this->filePath .'thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 

					if (!empty($data['image_2'])) {
						ErrorHandler::start();
						$originalFileName2 = trim($data['image_2']['name']);
						$dataArr['image_2']			= $this->rootDomain . $renderer->basePath().'/upload/thumb/'.$originalFileName2;
						move_uploaded_file($data['image_2']['tmp_name'], $this->filePath . $data['image_2']['name']);
						$imagePath   = $this->filePath . $data['image_2']['name'];
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save( $this->filePath . 'thumb/'.$originalFileName2);				
						ErrorHandler::stop(true);
					} 

					$dataArr['advertisement_type_id']		=	$request->getPost('advertisement_type_id') ; 
					$dataArr['duration']					=	$request->getPost('duration');										
					$dataArr['modified_on'] 				=	time() ;
					$dataArr['created_on'] 					=	time() ;
					$dataArr['active']						= 	$request->getPost('active'); 	
					$dataArr['notification'] 				= 0 ;
					$dataArr['content'] 					= $request->getPost('content'); 	
					$dataArr['url'] 						= $request->getPost('url'); 	

					

					$advertise->exchangeArray($dataArr);
					$this->getTable()->saveData($advertise);			
					return $this->redirect()->toRoute('advertisement', array('action'=>'index'));

			 }
		
        }
		
        return array('form' => $form, 'flashMessages'  => $this->flashmessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('deer-size', array('action'=>'add'));
		 }
		 
		$getData = $this->getDeerSizeTable()->getData($id);	
		$form = new DeerSizeForm();
		$form->bind($getData);
		//$form->get('submit')->setValue('Edit');
		$form->get('submit')->setAttribute('value', 'Update & Save');	
        $request = $this->getRequest();
		
        if ($request->isPost()) {	
           // $haircolor = new Haircolor();
            $form->setInputFilter($getData->getInputFilter());
            $form->setData($request->getPost());

			   if ($form->isValid()) {				

					$name 				=  $request->getPost('name') ; 
					$active 			=  $request->getPost('active'); 

					$dataArr['name']					=	trim($name);
					$dataArr['type']					=	$request->getPost('type');									
					$dataArr['active']					= 	$active ;	
					$dataArr['id']						= 	$request->getPost('id');	
					
					$getData->exchangeArray($dataArr);	
					$this->getDeerSizeTable()->saveData($getData);								
					return $this->redirect()->toRoute('deer-size', array('action'=>'index'));			
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
		$rowDetails = $this->getTable()->getData($id) ;
		
		$imgPath_1 = $this->filePath . $rowDetails->image_1;
		$imgPath_1_thumb = $this->filePath .'thumb/' .$rowDetails->image_1;
		
		$imgPath_2 = $this->filePath . $rowDetails->image_2;
		$imgPath_2_thumb = $this->filePath . 'thumb/' . $rowDetails->image_2;
		
		if (file_exists($imgPath_1)) unlink($imgPath_1);
		if (file_exists($imgPath_2)) unlink($imgPath_2);
		if (file_exists($imgPath_1_thumb)) unlink($imgPath_1_thumb);
		if (file_exists($imgPath_2_thumb)) unlink($imgPath_2_thumb);
		//echo '<pre>'; print_r($rowDetails ); exit;
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

