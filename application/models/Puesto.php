<?php

class Application_Model_Puesto extends Zend_Db_Table {

    protected $_name = 'puesto';
    protected $_primary = 'id_puesto';

    const ESTADO_INACTIVO = 0;
    const ESTADO_ACTIVO = 1;
    const ESTADO_ELIMINADO = 2;
    const TABLA = 'puesto';

    public function guardar($datos) {
        $id = 0;
        if (!empty($datos["id"])) {
            $id = (int) $datos["id"];
        }

        unset($datos["id"]);
        $datos = array_intersect_key($datos, array_flip($this->_getCols()));

        if ($id > 0) {
            $cantidad = $this->update($datos, 'id_puesto = ' . $id);
            $id = ($cantidad < 1) ? 0 : $id;
        } else {
            $id = $this->insert($datos);
        }

        return $id;
    }

    public function listado() {
        return $this->getAdapter()->select()->from($this->_name)->query()->fetchAll();
    }

    public function obtenerPuestos($unidad) {

        return $this->getAdapter()->select()->from(array('p' => self::TABLA),array('id_puesto','puesto' => 'descripcion',
            'numcor' => 'num_correlativo','cantidad'))
                ->joinInner(array('uo' => Application_Model_UnidadOrganica::TABLA), 'uo.id_uorganica = p.id_uorganica',
                        array('id_uorganica','unidad' => 'descripcion'))
                ->joinInner(array('o' => Application_Model_Organo::TABLA), 'o.id_organo = uo.id_organo',
                        array('id_organo','organo'))
                ->joinInner(array('g' => Application_Model_Grupo::TABLA), 'g.codigo_grupo = p.codigo_grupo',
                        array('codigo_grupo','grupo' => 'descripcion'))
                ->joinInner(array('f' => Application_Model_Familia::TABLA), 'f.codigo_familia = p.codigo_familia',
                        array('codigo_familia', 'familia' => 'descripcion'))
                ->joinInner(array('rp' => Application_Model_Rolpuesto::TABLA), 'rp.codigo_rol_puesto = p.codigo_rol_puesto',
                        array('codigo_rol_puesto','rpuesto' => 'descripcion'))
                ->where('p.id_uorganica = ?', $unidad)
                ->query()->fetchAll();
    }

}
