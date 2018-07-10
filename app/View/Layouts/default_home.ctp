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
        <!-- Etiqueta global de sitio (gtag.js) de Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-115639849-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-115639849-1');
        </script>
        <?php echo $this->Html->charset(); ?>
                <title>
			<?php echo $cakeDescription ?>:
			<?php echo $title_for_layout; ?>
		</title>
                <meta name="robots" content="index, follow">
                <meta name="author" content="CONTA Software SRL">
		<?php
			echo $this->Html->meta(
				'icon',
				'/img/logosolo.png',
				array('type' => 'icon')
			);			
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
        
    </head>
<body id="page-top">

		<div id="content">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
    <script>
        (function (w,i,d,g,e,t,s) {w[d] = w[d]||[];t= i.createElement(g);
          t.async=1;t.src=e;s=i.getElementsByTagName(g)[0];s.parentNode.insertBefore(t, s);
        })(window, document, '_gscq','script','//widgets.getsitecontrol.com/128516/script.js');
      </script>
</body>
</html>
