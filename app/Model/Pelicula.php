<?php
App::uses('AppModel', 'Model');
/**
 * Pelicula Model
 *
 * @property Formato $Formato
 */
class Pelicula extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'titulo';

	public $actsAs = array(
		'Cart.Buyable'
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Formato' => array(
			'className' => 'Formato',
			'foreignKey' => 'formato_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $hasAndBelongsToMany = array(
        'Genero' =>
            array(
                'className' => 'Genero',
                'joinTable' => 'generos_peliculas',
                'foreignKey' => 'pelicula_id',
                'associationForeignKey' => 'genero_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'with' => 'generos_peliculas'
            )
    );
}
