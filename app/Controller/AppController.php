<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
	
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $helpers = array(
		'Cart.Cart'
	);

	public $components = array(
		//'DebugKit.Toolbar',
		'Cart.CartManager',
	    'Session',
	    'Auth' => array(
                'loginAction' => array(
                    'controller' => 'users',
                    'action' => 'login'
                ),
                'loginRedirect' => array(
                    'controller' => 'peliculas',
                    'action' => 'lista',
                    'novedades'
                ),
                'logoutRedirect' => array(
                    'controller' => 'users',
                    'action' => 'login'               
                ),           
            )
	);

	public function beforeFilter() {
//		set_time_limit(0);
        $this->Auth->allow('home','login','terminosycondiciones','preguntas','novedades');
        $this->loadModel('Notification');
        $notificationsOptions = [
            'contain'=>[],
            //'fields'=>["*"],					
            'conditions'=>[
                'Notification.estudio_id'=>$this->Session->read('Auth.User.estudio_id'),			                    
                'Notification.readed'=>0,			                    
            ],		                
        ]; 
        $notifications = $this->Notification->find('all',$notificationsOptions);	
        $this->set('cantNotifications',count($notifications));
    }

	public function isAuthorized($user) {
	    // Admin can access every action
	    if (isset($user['tipo']) && $user['tipo'] === 'administrador') {
	        return true;
	    }

	    // Default deny
	    //todo permitido
	    
	    return true;
	}

}
class ParserUnlimited {// better recursive array merge function listed on the array_merge_recursive PHP page in the comments
	public function array_merge_recursive_distinct ( array &$array1, array &$array2 ){
		$merged = $array1;
		foreach ( $array2 as $key => &$value ) {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ){
				$merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
			} else {
				$merged [$key] = $value;
			}
		}
		return $merged;
	}
	public function my_parse_str($string, &$result) {
		if($string==='') return false;
		$result = array();
		// find the pairs "name=value"
		$pairs = explode('&', $string);
		foreach ($pairs as $pair) {
			// use the original parse_str() on each element
			parse_str($pair, $params);
			$k=key($params);
			if(!isset($result[$k])) $result+=$params;
			else $result[$k] = $this->array_merge_recursive_distinct($result[$k], $params[$k]);
		}
		return true;
	}

}