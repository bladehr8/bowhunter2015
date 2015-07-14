<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class HuntClubGameForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('huntclubgame');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'huntclubgame-light');
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
			 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'hunt_clubs_id',
			
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
				 'id' => 'hunt_clubs_id',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array(),
							'empty_option'  => 'Select hunt club'
             )
			));
		
		 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'light_conditions_id',
			 
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array(),
							'empty_option'  => 'Select light options'
             )
			));
		
		 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'regions_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array(),
							'empty_option'  => 'Select region'
             )
			));		
		
		
		
		
		
		
		
		


        $this->add(array(
            'name' => 'number_of_animals',			
			  'attributes' => array(
                'type'  => 'number',
				'class'  => 'form-control',
				'placeholder' => "Number of animals",
				'required'  => 'required',
				'min' => 1,
				'max' => 100
            ),	    
        ));
		
	        $this->add(array(
            'name' => 'time_to_complete',			
			  'attributes' => array(
                'type'  => 'number',
				'class'  => 'form-control',
				'placeholder' => "Time to complete(In Days)",
				'required'  => 'required',
				'min' => 1
				
				
				
            ),	
    
        ));	
		
		
		
		
		


		

		
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			
			 'attributes' => array(
				'required'  => 'required',
            ),
             'options' => array(
               'label' => 'Active Status ? ',
			    'label_attributes' => array(
						'class'  => 'radio-inline'
						),
			   
			   
                     'value_options' => array(
                             '0' => 'Inactive',
                             '1' => 'Active',
                     ),
             )
        ));
		
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
				'class'  => 'btn btn-bricky',
                'value' => 'Submit',
                'id' => 'hunt-club-game-button',
            ),
        ));
       
    }
}