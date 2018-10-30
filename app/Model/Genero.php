<?php
App::uses('AppModel', 'Model');
/**
 * Genero Model
 *
 
 */
class Genero extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';

	
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	
	public $hasAndBelongsToMany = array(
        'Pelicula' =>
            array(
                'className' => 'Pelicula',
                'joinTable' => 'generos_peliculas',
                'foreignKey' => 'genero_id',
                'associationForeignKey' => 'pelicula_id',
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
