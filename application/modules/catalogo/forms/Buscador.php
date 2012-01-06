<?php

class Catalogo_Form_Buscador extends Zend_Form
{

    public function init()
    {
        $this->setAction($this->getView()
            ->baseUrl().'/catalogo/index/buscar/')
            ->setMethod('post');

        $descripcion = $this->createElement('text', 'descripcion');
        $descripcion->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Ingrese un valor para la búsqueda')))
            ->removeDecorator('label')
            ->removeDecorator('htmlTag')
            ->setRequired(true);

        $this->addElement($descripcion)
            ->addElement('submit', 'buscar', array('label' => 'Buscar por descripción'));
        
        $this->buscar->removeDecorator('DtDdWrapper');

        $this->clearDecorators();
        
        $this->addDecorator('FormElements')
            ->addDecorator('HtmlTag', array(
                'tag' => '<div>',
                'class' => 'buscarForm'))
            ->addDecorator('Form')
            ->setAttrib('id', 'busqueda-productos');

        $this->descripcion->setDecorators(array(
            array('ViewHelper'),
            //array('Errors'),
            array('HtmlTag', array(
                'tag' => 'fieldset',
                'openOnly' => true))));   
        
        $this->buscar->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array(
                'tag' => 'fieldset',
                'closeOnly' => true))));   
        
    }


}

