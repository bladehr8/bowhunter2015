<?php
namespace Admin\Form;


use Zend\Form\Form;
use Zend\Form\Element;


use Admin\Model\ClothingTable; 
     

class ClothingForm extends Form
{
	
	public 	$regionArray = array(); 

   public function __construct($myformdata = null , $regionsdata = null)
    {
        // we want to ignore the name passed

		
        parent::__construct('clothing');
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-clothing');

		
		
		$this->add(array(
			 'type' => 'Zend\Form\Element\Hidden',
			 'name' => 'setId',
			
		 ));
	
	

	foreach($regionsdata as $region) 
	{
		$this->regionArray[$region->id] =  $region->name .'(' .$region->short_key . ')';	
		
	}
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));


		 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'region_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => $this->regionArray,
             )
     ));

	$this->add(array(
            'name' => 'name',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'placeholder' => "Enter Name",
				'required'  => 'required',
            ),
    
        ));	
		
	$this->add(array(
            'name' => 'price_gold',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'placeholder' => "Enter price gold",
				'required'  => 'required',
            ),
    
        ));	
	 
	 
	 
	 
	 
	 
	$this->add(array(
            'name' => 'price_bucks',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'placeholder' => "Enter price bucks",
				'required'  	=> 'required',
            ),
    
        ));
		
		$this->add(array(
            'name' => 'deer_panic',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'placeholder' => "Enter deer panic(Integer)",
				'required'  	=> 'required',
            ),
    
        ));	
		
		$this->add(array(
            'name' => 'player_facing_name',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'placeholder' => "Enter player facing name",
				'required'  	=> 'required',
            ),
    
        ));	
	
	
	$this->add(array(
            'name' => 'basic_alertness',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'placeholder' => "Enter basic alertness",
				'required'  	=> 'required',
            ),
    
        ));	
	
		
	$this->add(array(
            'name' => 'deer_reaction_range',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'placeholder' => "Enter deer reaction range (Integer)",
				'required'  	=> 'required',
            ),
    
        ));	
	
	
	
	

	
		
		
		
		
		
		

		
	/*	
	foreach($myformdata as $attributefield)	
	{

        $this->add(array(
            'name' => $attributefield['fieldName'],
			  'attributes' => array(
				'min'   => 1,
                'type'  => 'text',
				'class'  => 'form-control custom-form-control',
				'required'  => 'required',
				'placeholder' => "Enter ".$attributefield['fieldName'],
            ),
    
        ));
	}	
	*/	
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
				'class'  => 'btn btn-bricky custom-form-control',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}