<?php
            if (isset($_POST["numeroprocesos"])) {
                $nProc = $_POST["numeroprocesos"];
                $cpumemo = $_POST["filtro"];

                $cont = 0;
                $cmd = "tasklist /v /fo csv /nh";
                exec($cmd, $output);

                $doc = new DOMDocument('1.0');
                $doc->formatOutput = true;
                $raiz = $doc->createElement("TODOS");
                $raiz = $doc->appendChild($raiz);

                $arreglo = array();
                foreach ($output as $line) {  // ///////ciclo para poder mostrar los datos de los procesos 
                    $linea = explode(',', $line);
                    
                    ////cragamos toda la salida del cmd en un arreglo multidimencinal
                    $arreglo[] = array(
                        'nombre' => str_replace('"', '', $linea[0]),
                        'pid' => str_replace('"', '', $linea[1]),
                        "sesionnom" => str_replace('"', '', $linea[2]),
                        "sesionnum" => str_replace('"', '', $linea[3]),
                        "memoria" => str_replace('"', '', str_replace(' KB', '', $linea[4])),
                        "estado" => str_replace('"', '', $linea[5]),
                        "usuarionom" => str_replace('"', '', $linea[6]),
                        "cpu" => str_replace('"', '', $linea[7]),
                        "ventana" => str_replace('"', '', $linea[8]),
                    );

                    $cont++;
                }
                ////ordedamos el arreglo ya sea por mayo cpu o memoria
                if ($cpumemo == "1") {
                    uasort($arreglo, function ($a, $b) {
                        if ($a['cpu'] == $b['cpu']) {
                            return 0;
                        }
                        return ($a['cpu'] > $b['cpu']) ? -1 : 1;
                    });
                } else {
                    uasort($arreglo, function ($a, $b) {
                        if ($a['memoria'] == $b['memoria']) {
                            return 0;
                        }
                        return ($a['memoria'] > $b['memoria']) ? -1 : 1;
                    });
                }

                $cont = 0;
                foreach ($arreglo as $line) {  // ///////ciclo para poder mostrar los datos de los procesos 
                    if ($cont >= 0 && $cont <= $nProc) {
                        //echo substr($line,25,16 );
                        $res = $line["nombre"];
                        $res1 = $line["pid"];
                        $res2 = $line["sesionnom"];
                        $res3 = $line["sesionnum"];
                        $res4 = $line["memoria"];
                        $res5 = $line["estado"];
                        $res6 = $line["usuarionom"];
                        $res7 = $line["cpu"];
                        $res8 = $line["ventana"];

                        //echo $res;
                        ///////////////aqui se generan los nodos del archivo xml
                        $proceso = $doc->createElement("proceso");
                        $proceso = $raiz->appendChild($proceso);

                        $nombre = $doc->createElement("nombre");
                        $nombre = $proceso->appendChild($nombre);
                        $textnombre = $doc->createTextNode(rtrim($res));
                        $textnombre = $nombre->appendChild($textnombre);

                        $pid = $doc->createElement("pid");
                        $pid = $proceso->appendChild($pid);
                        $textpid = $doc->createTextNode(ltrim(rtrim($res1)));
                        $textpid = $pid->appendChild($textpid);


                        $nombreses = $doc->createElement("nombreses");
                        $nombreses = $proceso->appendChild($nombreses);
                        $textnombreses = $doc->createTextNode(ltrim(rtrim($res2)));
                        $textnombreses = $nombreses->appendChild($textnombreses);

                        $numses = $doc->createElement("numeroses");
                        $numses = $proceso->appendChild($numses);
                        $textpriori = $doc->createTextNode(ltrim(rtrim($res3)));
                        $textpriori = $numses->appendChild($textpriori);

                        $memoria = $doc->createElement("Cmemoria");
                        $memoria = $proceso->appendChild($memoria);
                        $textmemoria = $doc->createTextNode(ltrim(rtrim($res4 . ' KB')));
                        $textmemoria = $memoria->appendChild($textmemoria);

                        $estadoproc = $doc->createElement("estadoproc");
                        $estadoproc = $proceso->appendChild($estadoproc);
                        $textdescr = $doc->createTextNode(ltrim(rtrim($res5)));
                        $textdescr = $estadoproc->appendChild($textdescr);


                        $usuario = $doc->createElement("usuario");
                        $usuario = $proceso->appendChild($usuario);
                        $textdescr = $doc->createTextNode(ltrim(rtrim($res6)));
                        $textdescr = $usuario->appendChild($textdescr);

                        $tiempocpu = $doc->createElement("tiempocpu");
                        $tiempocpu = $proceso->appendChild($tiempocpu);
                        $textdescr = $doc->createTextNode(ltrim(rtrim($res7)));
                        $textdescr = $tiempocpu->appendChild($textdescr);

                        $titulovent = $doc->createElement("titulovent");
                        $titulovent = $proceso->appendChild($titulovent);
                        $textdescr = $doc->createTextNode(ltrim(rtrim($res8)));
                        $textdescr = $titulovent->appendChild($textdescr);



                        $prioridad = 0;
                        if ($res6 == "N/D" || $res6 == "NT AUTHORITY\SYSTEM") {

                            $prioridad = 1;
                            $res6='SYSTEM';
                        }


                        $tprioridad = $doc->createElement("prioridad");
                        $tprioridad = $proceso->appendChild($tprioridad);
                        $textprioridad = $doc->createTextNode(ltrim(rtrim($prioridad)));
                        $textprioridad = $tprioridad->appendChild($textprioridad);
                        ?>
                <tr>
                    <td>
                        <?php echo $res ?>
                    </td>
                    <td>
                        <?php echo $res1 ?>
                    </td>
                    <td>
                        <?php echo $res6 ?>
                    </td>
                    <td>
                        <?php echo $res ?>
                    </td>
                    <td>
                        <?php echo $res4 . ' KB' ?>
                    </td>
                    <td>
                        <?php echo $res7 ?>
                    </td>
                    
                </tr>
                <?php
                    }
                    $cont++;
                }
                ////escribimos lo datos en el archivo xml
                $nombre_fichero = '/Xampp/htdocs/procesos/xml/procesos.xml';
                if (is_readable($nombre_fichero)) {
              
                } else {
                    ?>
                    <script>
                        window.alert('no existe la ruta /Xampp/htdocs/procesos/xml/procesos.xml donde se pueda guardar el archivo por devera modificar la ruta donde se encuentra el proyecto')
                    </script>
                    <?php
                    
                }
            }
            
            ?>