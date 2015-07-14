<?php
namespace Application\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;

//use Zend\ServiceManager\ServiceLocatorAwareInterface;
//use Zend\ServiceManager\ServiceLocatorInterface;



class MyModePlugin extends AbstractPlugin 
{
	protected $othersGameTable;
	public function getOthersGameTable()
    {
        if (!$this->othersGameTable) {
            $sm = $this->getServiceLocator();
            $this->othersGameTable = $sm->get('Admin\Model\OthersGameTable');
        }
        return $this->othersGameTable;
    }	



}