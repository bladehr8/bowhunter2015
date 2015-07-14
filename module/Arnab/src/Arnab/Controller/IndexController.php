<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Arnab\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Player;          // <-- Add this import
//use Admin\Form\AlbumForm;       // <-- Add this import
use Admin\Model\PlayerTable;     // <-- Add this import

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
	
	
	
	echo "kkk";
	exit();
        //return new ViewModel();
    }
	
	public function getlistAction()
    {   // Action used for GET requests without resource Id
	
		//return array("data" => $album);
	
	    $data=array('id'=> 1, 'name' => 'Mothership', 'band' => 'Led Zeppelin');
        echo json_encode($album);
		exit();
           
      
    }
	
}
