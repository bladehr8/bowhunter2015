<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class GameVideoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('gamevideo');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype' , 'multipart/form-data');
		$this->setAttribute('class', 'form-gamevideo');
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));


        $this->add(array(
            'name' => 'title',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter screen-shots title",
				'required'  => 'required',
            ),	
    
        ));
		
		 $this->add(array(
        'name'      => 'short_note',
        'attributes'=> array(
								'id'    => 'summary',
								'type'  => 'textarea',
								'class' => 'form-control',
								'placeholder' => "Enter short description here",
								),

		));	
		
		
		
		
		
		

		
		



		$this->add(array(
			'type' => 'url',
			'name' => 'youtube_link',
			'attributes' => array(
				'class'  => 'form-control',
				'required'  => 'required',
				'placeholder' => "Enter youtube link of your video",				
			)
		));


	
		
		
		

		
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			
			 'attributes' => array(
				'required'  => 'required',
            ),
             'options' => array(
               'label' => 'Is Active ?',
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
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}