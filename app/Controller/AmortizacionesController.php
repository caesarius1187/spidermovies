<?php
App::uses('AppController', 'Controller');
/**
 * Eventosimpuestos Controller
 *
 * @property Amortizaciones $Asiento
 * @property PaginatorComponent $Paginator
 */
class AmortizacionesController extends AppController {

    public $components = array('Paginator');
    public function delete($id = null) {
        $this->Amortizacione->id = $id;
        if (!$this->Amortizacione->exists()) {
                throw new NotFoundException(__('Invalid Amortizacione'));
        }
        $this->request->onlyAllow('post');
        $data = array();
        if ($this->Amortizacione->delete()) {
                $data['respuesta'] = 'Amortizacion especial eliminada con exito.';
                $data['error'] = 0;
        } else {
                $data['respuesta'] = 'Amortizacion especial NO eliminada. Por favor intente mas tarde.';
                $data['error'] = 1;
        }
        $this->layout = 'ajax';
        $this->set('data', $data);
        $this->render('serializejson');
    }
}
