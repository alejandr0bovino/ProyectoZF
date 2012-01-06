<?php

class Admin_Form_Producto extends Zend_Form
{

    public function init()
    {
        $this->setAction($this->getView()
            ->baseUrl().'/admin/catalogo/guardar/')
            ->setMethod('post');
        
        $id = $this->createElement('hidden', 'id');
        
        $descripcion = $this->createElement('text', 'descripcion');        
        $descripcion->setLabel('Descripción')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
            ->addValidator('alnum', true, array('allowWhiteSpace' => true, 'messages' => array('notAlnum' => "El valor no es alfanúmerico")))
            ->setRequired(true);        
        
        $precio = $this->createElement('text', 'precio');        
        $precio->setLabel('Precio')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
            ->addValidator('int', true, array('messages' => array('notInt' => "El valor no es un número entero")))
            ->setRequired(true);

        $cantidad = $this->createElement('text', 'cantidad');        
        $cantidad->setLabel('Cantidad')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
            ->addValidator('int', true, array('messages' => array('notInt' => "El valor no es un número entero")))
            ->setRequired(true);        


        $this->addElement($id)
        ->addElement($descripcion)
        ->addElement($precio)
        ->addElement($cantidad)
        ->addElement('submit', 'enviar', array('label' => 'Enviar'));

      
        
        $this->clearDecorators();
       
        $this->addDecorator('FormElements')
            ->addDecorator('HtmlTag', array(
                'tag' => '<div>',
                'class' => 'altaProductoForm'))
            ->addDecorator('Form');
       
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array(
                'requiredSuffix' => ' *',
                'escape' => false,
                'separator' => ' ')),
            array('HtmlTag', array(
                'tag' => 'fieldset',
                'class' => 'element_form clearfix'))));
        
        $this->id->removeDecorator('HtmlTag'); 

        $this->enviar->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array(
                'tag' => 'div',
                'class' => 'element_form submit'))));                
        
    }


}

