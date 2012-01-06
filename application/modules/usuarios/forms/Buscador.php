<?php

class Usuarios_Form_Buscador extends Zend_Form
{

    public function init()
    {
        $this->setAction($this->getView()
            ->baseUrl().'/usuarios/index/buscar/')
            ->setMethod('post');

        $nombre = $this->createElement('text', 'nombre');
        $nombre->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Ingrese un valor para la búsqueda')))
            ->removeDecorator('label')
            ->removeDecorator('htmlTag')
            ->setRequired(true);

        $this->addElement($nombre)
            ->addElement('submit', 'buscar', array('label' => 'Buscar por nombre'));
        
        $this->buscar->removeDecorator('DtDdWrapper');

        $this->clearDecorators();
        
        $this->addDecorator('FormElements')
            ->addDecorator('HtmlTag', array(
                'tag' => '<div>',
                'class' => 'buscarForm'))
            ->addDecorator('Form')
            ->setAttrib('id', 'busqueda-usuarios');

        $this->nombre->setDecorators(array(
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

