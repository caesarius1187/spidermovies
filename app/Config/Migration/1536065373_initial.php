<?php
class Initial extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'initial';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'formatos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'nombre' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'precio' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
				'notifications' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'estudio_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'cliente_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'texto' => array('type' => 'string', 'null' => false, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'controller' => array('type' => 'string', 'null' => false, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'impcli_id' => array('type' => 'string', 'null' => false, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'periodo' => array('type' => 'string', 'null' => false, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'action' => array('type' => 'string', 'null' => false, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'params' => array('type' => 'string', 'null' => false, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'fecha' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'readed' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
				),
				'peliculas' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'formato_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'nombre' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'reseña' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 500, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'video' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 500, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'imagen' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 500, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'actores' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 500, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
				'users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 5, 'unsigned' => false, 'key' => 'primary'),
					'dni' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'telefono' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'cel' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'mail' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'nombre' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'matricula' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'username' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'password' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'formatos', 'notifications', 'peliculas', 'users'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
