<?php
namespace Admin\Form;


use Zend\Form\Form;
use Zend\Form\Element;
use Admin\Model\WeaponTable;    

class WeaponForm extends Form
{
	

   public function __construct($myformdata = null)
    {
        // we want to ignore the name passed
        parent::__construct('weapon');
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-weapon');
		 //$weapon = new WeaponTable();
		
		//$mydata=$weapon->getWeaponTable->fetchAll(2);
		//foreach($myformdata as $attributefield){ 
		//echo "<pre>";
		//print_r($attributefield);
		
		//}
		 
		 //exit();
		
		
		$this->add(array(
			 'type' => 'Zend\Form\Element\Hidden',
			 'name' => 'setId',
			
		 ));
	
	
	$this->add(array(
            'name' => 'name',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Weapon Name",
				'required'  => 'required',
            ),
    
        ));	
		
	$this->add(array(
            'name' => 'price',
			  'attributes' => array(
                'type'  => 'number',
				'class'  => 'form-control box-top-space',
				'placeholder' => "Weapon price",
				'required'  => 'required',
            ),
    
        ));		
		
	foreach($myformdata as $attributefield)	
	{

        $this->add(array(
            'name' => $attributefield['fieldName'],
			  'attributes' => array(
				'min'   => 1,
                'type'  => 'text',
				'class'  => 'form-control box-top-space',
				'required'  => 'required',
				'placeholder' => "Enter ".$attributefield['fieldName'],
            ),
    
        ));
	}	
		
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
				'class'  => 'btn btn-bricky box-top-space',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}