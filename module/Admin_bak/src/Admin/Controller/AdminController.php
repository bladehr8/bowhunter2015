<?php

namespace Admin\Controller;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Admin;          // <-- Add this import
use Admin\Form\AdminForm;       // <-- Add this import
use Zend\Session\Container;
use Zend\Form\Annotation\AnnotationBuilder;




class AdminController extends AbstractActionController
{
	protected $adminTable;
	protected $form;
    protected $storage;
    protected $authservice;
	
	public function preDispatch()
    {
	//echo "oook";
	//exit();
       
    }

     
    public function getAuthService()
    {
	
	
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
      $this->authservice;
       return $this->authservice;
    }
	
	public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()->get('Admin\Model\MyAuthStorage');
        }
         
        return $this->storage;
    }
	
	
	
	
	
    public function indexAction()
    {	
		$layout = $this->layout();
		$layout->setTemplate('layout/login');
		
		$form = new AdminForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
			//echo '<pre>'; print_r($request->getPost());
			//exit;
            $admin = new Admin();
            $form->setInputFilter($admin->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {	
						

				$username 		=  $request->getPost('email') ; 
				$password 	=  $request->getPost('password') ; 
			
				
				
				$this->getAuthService()->getAdapter()->setIdentity($username)->setCredential($password);
									   
									  
                                        
                $result = $this->getAuthService()->authenticate();
				
               foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                } 
                 
                if ($result->isValid()) {
				
				

                    //$redirect = 'success';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                             ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
					$this->getAuthService()->getStorage()->write($request->getPost('email'));
					return $this->redirect()->toRoute('admin', array('action'=>'dashboard'));
                } else {
				
					return $this->redirect()->toRoute('admin', array('controller' => 'index', 'action'=>'index','id'=>0));
				}
				
				
				
				
				/*if($email == 'avishek@matrixnmedia.com' && $password == 'welcome1')
				{					
					$sessionAdmin = new Container('admin');
					$sessionAdmin->email = $email ;
					$sessionAdmin->isvalid = "yes";
					return $this->redirect()->toRoute('admin', array('action'=>'dashboard'));
				}
				else
				{
					return $this->redirect()->toRoute('admin', array('controller' => 'index', 'action'=>'index','id'=>0));
					
					
				} */

            }
        }
        return array('form' => $form,'messages'  => $this->flashmessenger()->getMessages());

	
	
		//return new ViewModel();
		
		/*
		return new ViewModel(array(
            'admins' => $this->getAdminTable()->fetchAll(),
        ));
		*/
		
    }
	
	public function dashboardAction()
	{
	
		/* if (Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            if ('logout' != $this->getRequest()->getActionName()) {
				return $this->redirect()->toRoute('admin', array('action'=>'dashboard'));
               // $this->_helper->redirector('admin', 'dashboard');
            }
        } else {
            // If they aren't, they can't logout, so that action should 
            // redirect to the login form
            if ('logout' == $this->getRequest()->getActionName()) {
			return $this->redirect()->toRoute('admin', array('action'=>'index'));
               // $this->_helper->redirector('index');
            }
        } */
		
		
		$sessionAdmin = new Container('admin');
		if ($sessionAdmin) {
		echo $sessionAdmin->username;
		}
		return new ViewModel();
	}
	
	public function getAdminTable()
    {
        if (!$this->adminTable) {
            $sm = $this->getServiceLocator();
            $this->adminTable = $sm->get('Admin\Model\AdminTable');
        }
        return $this->adminTable;
    }
	
	
	
	
	public function logoutAction()
	{
		/*$session_admin = new Container('admin');
		$session_admin->getManager()->getStorage()->clear('admin');
		return $this->redirect()->toRoute('admin', array('action'=>'index')); */
		
		$this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
         
        $this->flashmessenger()->addMessage("You've been logged out");
       // return $this->redirect()->toRoute('index');
	   return $this->redirect()->toRoute('admin', array('action'=>'index'));
	}
	


}

