<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Model\PlayerRank;          // <-- Add this import
use Admin\Form\PlayerRankForm;       // <-- Add this import


use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ErrorHandler;


class PlayerRankController extends AbstractActionController
{
	
	protected $playerRankTable;

	public $thumbWidth = 100;
	public $thumbHeight = 100;
	
	public function init()
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){				
          return $this->redirect()->toRoute('admin', array('action'=>'index'));
        } 
    }
	
	public function getPlayerRankTable()
    {
        if (!$this->playerRankTable) {
            $sm = $this->getServiceLocator();
            $this->playerRankTable = $sm->get('Admin\Model\PlayerRankTable');
        }
        return $this->playerRankTable;
    }
	
	public function indexAction()	{ 
	$this->init();	
	 return new ViewModel(array(
			'rows' => $this->getPlayerRankTable()->fetchAll(),
			'flashMessages'  => $this->flashmessenger()->getMessages(),
		));

	}
	
	public function appendMyJs()
	{                 
		$this->getViewHelper('inlineScript')->appendScript('jQuery(document).ready(function() {  FormElements.init(); });',	        
	        'text/javascript', array('noescape' => true));
	}

	protected function getViewHelper($helperName)
	{
		return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
	}	
	
	

	
	public function addAction()	{
		$this->init();
		$this->appendMyJs();
		$form = new PlayerRankForm();
        //$form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
		
		$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );
		
			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

            $playerRank = new PlayerRank();
			//$playerRank->setDbAdapter($dbAdapter);
            
			
		
			
			$form->setInputFilter($playerRank->getInputFilter());		
			
			
			
			
            $form->setData($post);

           if ($form->isValid()) {
					$data = $form->getData();
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','jpe','jpeg','png');

					if (!empty($data['field_icon'])) {
						ErrorHandler::start();
						$originalFileName = trim($data['field_icon']['name']);
						//$ext = end(explode('.',$originalFileName));
						$efilename = explode('.', $originalFileName);
						//$ext = $efilename[count($originalFileName) - 1];
						$ext = end( $efilename );
						//echo $ext; exit();
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$dataArr['field_icon']			=	$originalFileName;
						move_uploaded_file($data['field_icon']['tmp_name'], 'public/upload/'.$data['field_icon']['name']);
						$imagePath   = 'public/upload/'.$data['field_icon']['name'];
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 

				$dataArr['name']= $request->getPost('name') ; 
				$dataArr['min_exp']= $request->getPost('min_exp') ; 
				$dataArr['max_exp']= $request->getPost('max_exp') ; 
				$dataArr['active']= $request->getPost('active'); 
				
				
				$playerRank->exchangeArray($dataArr);
                $this->getPlayerRankTable()->saveData($playerRank);
				$this->flashMessenger()->addMessage('Successfully added.');
			     return $this->redirect()->toRoute('player-rank', array('action'=>'index'));
		
         }
		
        }
		
        return array('form' => $form,'messages'  => $this->flashMessenger()->getMessages());	
	
	}
	

	public function editAction() {
	
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			 return $this->redirect()->toRoute('player-rank', array('action'=>'add'));
		 }
		 
		$playerRankData = $this->getPlayerRankTable()->getData($id);	
		 
		$form = new PlayerRankForm();
		$form->bind($playerRankData);
		//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$playerRankData->setDbAdapter($dbAdapter);
		
        $request = $this->getRequest();
        if ($request->isPost()) {

		
		
			$post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray() );		
            $playerRank = new PlayerRank();
			//$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');			
			//$playerRank->setDbAdapter($dbAdapter);

			$form->getInputFilter()->get('field_icon')->setRequired(false);
			//$playerRank->getInputFilter()->get('field_icon')->setAllowEmpty(false);

			//$playerRank->getInputFilter()->get('name')->setAllowEmpty(false);



			
            $form->setInputFilter($playerRank->getInputFilter());
            $form->setData($post);

			   if ($form->isValid()) {

					$data = $form->getData();
					//print $data->field_icon['error'];
					//echo '<pre>'; print_r($data); exit;
					$isValid = true; 
					$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
					$ext_type = array('gif','jpg','jpe','jpeg','png');

					if (!empty($data->field_icon['name'])) {
						ErrorHandler::start();
						$originalFileName = trim($data->field_icon['name']);
						//$ext = end(explode('.',$originalFileName));
						$efilename = explode('.', $originalFileName);
						//$ext = $efilename[count($originalFileName) - 1];
						$ext = end( $efilename );
						//echo $ext; exit();
						
						if( !in_array($ext, $ext_type) ) { 
							throw new \Exception("Invalid file extenstion - $ext ");
						}
						$dataArr['field_icon']			=	$originalFileName;
						move_uploaded_file($data->field_icon['tmp_name'], 'public/upload/'.$data->field_icon['name'] );
						$imagePath   = 'public/upload/'.$data->field_icon['name'];
						$thumb       = $thumbnailer->create($imagePath, $options = array(), $plugins = array());
						$thumb->resize($this->thumbHeight, $this->thumbWidth);
						$thumb->save('public/upload/thumb/'.$originalFileName);
						ErrorHandler::stop(true);
					} 
					

			   
					$dataArr['name']		= $request->getPost('name') ; 
					$dataArr['min_exp']		= $request->getPost('min_exp') ; 
					$dataArr['max_exp']		= $request->getPost('max_exp') ; 
					$dataArr['active']		= $request->getPost('active'); 
					
					
					
					$dataArr['id']= $request->getPost('id');	
					//echo '<pre>'; print_r($dataArr); exit();
					
					$playerRank->exchangeArray($dataArr);
					$this->getPlayerRankTable()->saveData($playerRank);	
					$this->flashMessenger()->addMessage('Successfully updated.');
					return $this->redirect()->toRoute('player-rank', array('action'=>'index'));			
			 }
		
        }

	
	 return array(
         'id' => $id,
         'form' => $form,
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
		$this->getPlayerRankTable()->deleteData($id);
		$this->flashMessenger()->addMessage('Successfully deleted this data.');
		return $this->redirect()->toRoute('player-rank' , array('action' => 'index'));
		}
	}
	
	
	public function detailsAction()
	{
		$this->init();	
		$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(array('haircolorDetails' => $this->getHaircolorTable()->getHaircolor($id) ,)
		);
		
	}
	
	
	

	
	

}

