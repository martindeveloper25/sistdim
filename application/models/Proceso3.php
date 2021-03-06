<?php

class Application_Model_Proceso3 extends Zend_Db_Table
{

    protected $_name = 'proceso_n3';
    protected $_primary = 'id_proceso_n3';

    const ESTADO_INACTIVO = 0;
    const ESTADO_ACTIVO = 1;
    const ESTADO_ELIMINADO = 2;
    const TABLA = 'proceso_n3';
    const TIENE_HIJO = 1;

    public function guardar($datos)
    {
        $id = 0;
        if (!empty($datos["id_proceso_n3"])) {
        	$id = (int) $datos["id_proceso_n3"];
        }
        
        unset($datos["id"]);
        $datos = array_intersect_key($datos, array_flip($this->_getCols()));
        
        if ($id > 0) {
        	$cantidad = $this->update($datos, $this->_primary .' = ' . $id);
        	$id = ($cantidad < 1) ? 0 : $id;
        } else {
        	$id = $this->insert($datos);
        }
        
        return $id;
    }

    public function listado()
    {
        return $this->getAdapter()->select()->from($this->_name)->query()->fetchAll();
    }
    
    public function combo($proyecto)
    {
        return $this->getAdapter()->select()->from($this->_name,array('key' => $this->_primary, 'value' => 'descripcion'))
                ->where('estado = ?',self::ESTADO_ACTIVO)
                ->where('id_proyecto = ?', $proyecto)
                ->order('descripcion asc')
                ->query()->fetchAll();
    }
    
    public function obtenerProcesos3($proceso2)
    {
        return $this->getAdapter()->select()->from($this->_name)
                ->where('estado = ?',self::ESTADO_ACTIVO)
                ->where('id_proceso_n2 = ?', $proceso2)
                //->where('tiene_actividad = ?', 0)
                ->order('descripcion asc')
                ->query()->fetchAll();
    }
    
    //Actividad
    //Si viene nivel 1 solo listar los que no tienen hijos
    public function obtenerProcesos3Actividad($proceso2, $nivel) {

        $select = $this->getAdapter()->select()->from($this->_name)
                ->where('id_proceso_n2 = ?', $proceso2)
                ->where('estado = ?', self::ESTADO_ACTIVO);

        if ($nivel == 3) {
            $select->where('tiene_hijo <> ?', self::TIENE_HIJO);
        }
        return $select->query()->fetchAll();
    }
    
    /*
     * Función para validar los procesos nivel 3 a la hora de eliminar el proceso de nivel 2
     */
    public function obtenerProcesos3Val($proceso0) {
        
        
        return $this->getAdapter()->select()->from(array('n3' => $this->_name))
                        ->where('estado = ?', self::ESTADO_ACTIVO)
                        ->where('id_proceso_n2 = ?', $proceso0)
                        ->query()->fetchAll();
    }
    
    public function eliminarProcesoN3($proceso) {
        
        $data['estado'] = self::ESTADO_ELIMINADO;
        $this->update($data, $this->_primary . ' = ' . $proceso);
        
    }



}

