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
use Zend\Db\Sql\Sql;


class PlayerController extends AbstractActionController
{
	
	protected $playerTable;
	
	public function getPlayerTable()
    {
        if (!$this->playerTable) {
            $sm = $this->getServiceLocator();
            $this->playerTable = $sm->get('Admin\Model\PlayerTable');
        }
        return $this->playerTable;
    }

	
	
	
	
    public function indexAction()
    { 
	 $sm = $this->getServiceLocator();
				$adapter = $sm->get('Zend\Db\Adapter\Adapter');
	$sql = new Sql($adapter);
$select = $sql->select();
$select->from('player');
$select->where(array('id' => 1));

$selectString = $sql->getSqlStringForSqlObject($select);
$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
$result=stmt->execute($parameters)
echo "<pre>";
	print_r($results);
	exit();
	
	//echo "<pre>";
	//print_r($this->getPlayerTable()->fetchAll());
	//exit();
	           
				//$this->playerTable->fetchAll();
				//print_r($this->playerTable->fetchAll());
		
	 return new ViewModel(array(
            'players' => $this->getPlayerTable()->fetchAll(),
        ));
	
	}
	
	
	

	
	

}

