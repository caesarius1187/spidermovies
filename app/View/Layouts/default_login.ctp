<?php
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */

$cakeDescription = __d('conta.com.ar', 'CONTA');
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
			<?php echo $cakeDescription ?>:
			<?php echo $title_for_layout; ?>
		</title>
		<?php
			echo $this->Html->meta(
				'icon',
				'/img/logosolo.png',
				array('type' => 'icon')
			);
			echo $this->Html->script('jquery-1.8.2.min.js');
			echo $this->Html->script('supersized.3.2.7.min.js');
			echo $this->Html->script('supersized-init.js');
			echo $this->Html->script('scripts.js');
			echo $this->Html->css('http://fonts.googleapis.com/css?family=PT+Sans:400,700');
			echo $this->Html->css('reset.css');
			echo $this->Html->css('supersized.css');
			echo $this->Html->css('style.css');

			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
	</head>
<body>

		<div id="content">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
</body>
</html>
