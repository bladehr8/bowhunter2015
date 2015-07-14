<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class NewsForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('news');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype' , 'multipart/form-data');
		$this->setAttribute('class', 'form-news');
		
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
				'placeholder' => "Enter page title",
				'required'  => 'required',
            ),	
    
        ));
		
	        $this->add(array(
            'name' => 'external_url',			
			  'attributes' => array(
                'type'  => 'url',
				'class'  => 'form-control',
				'placeholder' => "Enter external url for news ( Start with http:// )",
				'required'  => 'required',
            ),	
    
        ));	
		
		
		
		
		
		/*
		 $this->add(array(
        'name'      => 'short_note',
        'attributes'=> array(
								'id'    => 'summary',
								'type'  => 'textarea',
								'class' => 'form-control',
								'placeholder' => "Enter short description here",
								),

		));	
		
		
		*/
		
		
		
	  $this->add(array(
        'name'      => 'news_content',
        'attributes'=> array(
								'id'    => 'news_content',
								'type'  => 'textarea',
								'class' => 'ckeditor form-control',
								'placeholder' => "Enter details description here",
								'cols' => 10,
								'rows' =>10,
								),

    ));	
		
		
		

/*

		$this->add(array(
			'type' => 'Zend\Form\Element\File',
			'name' => 'field_image',
			'attributes' => array(
				'class' => 'file-input',				
			)
		));

*/
	
		
		
		

		
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