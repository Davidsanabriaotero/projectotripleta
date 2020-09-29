<?php
include('../declaracion.html');
include('../navbar.html');
include('../jumbotron.html');
?>
        
        <?php
        $nombre_fichero = '/Xampp/htdocs/procesos/xml/procesos.xml';
        if (is_readable($nombre_fichero)) {
            
        } else {
            ?>

            <script> window.alert('no existe la ruta /Xampp/htdocs/proceso/xml/procesos.xml, por lo tanto no se sobreescribirá el archivo procesos.xml. devera modificar la ruta donde se encuentra el proyecto en la linea 7, 306, 309 del documento index.php. colocarle la nueva direccion de alojamiento en su equipo ')</script>
            <?php
        }
        ?>
<?php
include('../formulario.html');
include('../tabla.html');
include('tabla.php');
include('../cierre.html');
?>


        
       
