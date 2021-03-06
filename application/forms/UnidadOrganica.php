<?php

class Application_Form_UnidadOrganica extends Zend_Form
{
    private $_organo;
    
    public function init()
    {
        $sesion_usuario = new Zend_Session_Namespace('sesion_usuario');
        $proyecto = $sesion_usuario->sesion_usuario['id_proyecto'];

        $this->_organo = new Application_Model_Organo;
        
        $this->setAttrib('id', 'form');
        
        $dataOrgano = $this->_organo->combo($proyecto);
        array_unshift($dataOrgano,array('key'=> '', 'value' => 'Seleccione'));
        
        $naturaleza = new Zend_Form_Element_Select('id_organo');
        $naturaleza->setLabel('Órgano:');
        $naturaleza->setRequired();
        $naturaleza->addFilter('StripTags');
        $naturaleza->setMultiOptions($dataOrgano);
        $this->addElement($naturaleza);
        
        $unidad = new Zend_Form_Element_Textarea('descripcion');
        $unidad->setLabel('Unidad Orgánica:');
        $unidad->setRequired();
        $unidad->setAttrib('rows', 2);
        $unidad->addFilter('StripTags');
        $this->addElement($unidad);
        
        $siglas = new Zend_Form_Element_Text('siglas');
        $siglas->setLabel('Siglas:');
        $siglas->addFilter('StripTags');
        $this->addElement($siglas);

        /*
        $estado = new Zend_Form_Element_Select('estado');
        $estado->setLabel('Estado:');
        $estado->setRequired();
        $estado->setMultiOptions(array('1' => 'Activo', '0' => 'Inactivo'));
        $estado->addFilter('StripTags');
        $this->addElement($estado);
         */
   
    }

}

