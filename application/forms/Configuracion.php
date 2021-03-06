<?php

class Application_Form_Configuracion extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('id', 'form');
        
        $nombre = new Zend_Form_Element_Text('nombre');
        $nombre->setLabel('Nombre:');
        $nombre->setRequired();
        $nombre->addFilter('StripTags');
        $nombre->setAttrib('maxlength', 50);
        $nombre->addValidator(new Zend_Validate_StringLength(array('min' => 3)));
        $this->addElement($nombre);
        
        $descripcion = new Zend_Form_Element_Text('descripcion');
        $descripcion->setLabel('Descripción:');
        $descripcion->setRequired();
        $descripcion->addFilter('StripTags');
        $descripcion->setAttrib('maxlength', 50);
        $descripcion->addValidator(new Zend_Validate_StringLength(array('min' => 3)));
        $this->addElement($descripcion);
        
        $valor = new Zend_Form_Element_Text('valor');
        $valor->setLabel('Valor:');
        $valor->setRequired();
        $valor->addFilter('StripTags');
        $valor->setAttrib('maxlength', 50);
        $valor->addValidator(new Zend_Validate_StringLength(array('min' => 1)));
        $this->addElement($valor);
        
        $estado = new Zend_Form_Element_Select('estado');
        $estado->setLabel('Estado:');
        $estado->setRequired();
        $estado->setMultiOptions(array('1' => 'Activo', '0' => 'Inactivo'));
        $estado->addFilter('StripTags');
        $this->addElement($estado);
        
   
    }


}

