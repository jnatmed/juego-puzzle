<?php

namespace App\Core\Database;

use PDO;
use Exception;
use App\Core\App;
use PDOException;

class QueryBuilder
{
    /**
     * The PDO instance.
     *
     * @var PDO
     */
    protected $pdo;
    protected $logger;

    /**
     * Create a new QueryBuilder instance.
     *
     * @param PDO $pdo
     */
    public function __construct($pdo, $logger = null)
    {
        $this->pdo = $pdo;
        $this->logger = ($logger) ? $logger : null;
    }

    /**
     * Select all records from a database table.
     *
     * @param string $table
     */
    public function selectAll($table)
    {
        $statement = $this->pdo->prepare("select id_usuario, alias, email from {$table}");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function selectAllByUser($id_usuario_actual)
    {
        $consulta = "SELECT id_partida, progreso, puntaje FROM partida WHERE id_usuario = :id_usuario";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindParam(':id_usuario', $id_usuario_actual, PDO::PARAM_STR);
        $stmt->execute();

        // Recuperar los resultados
        $partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // echo("<pre>");
        // var_dump($partidas);

        if ($partidas) {
                return [
                    'estado' => 'ok',
                    'listado' => $partidas,
                    'descripcion' => 'QueryBuilder : inserción correcta'
                ];
            } else {
                return [
                    'estado' => 'error',
                    'descripcion' => 'QueryBuilder : error al traer partidas'
                ];
        }
    }

    public function traerUltima($id_usuario){

        $consulta = "SELECT MAX(id_partida) AS max_id FROM partida WHERE id_usuario = :id_usuario";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $stmt->execute();
        
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($fila['max_id'] !== null) {
            // Obtener el último id_partida y aumentarlo en 1
            // echo("<pre>");
            // var_dump($fila);

            $ultimo_id_partida = $fila['max_id'];
            return $ultimo_id_partida;
        }else {
            return 0;
        }
        
    }

    public function crearNueva($id_usuario, $id_partida) {
        try {
            // echo("<pre>");
            // var_dump($id_usuario);
            
            // Preparar la consulta de inserción
            $consulta = "INSERT INTO partida (id_partida, id_usuario, estado_partida, progreso, imagenes, puntaje) VALUES (:id_partida, :id_usuario, NULL, 'iniciada', NULL, NULL)";
            $stmt = $this->pdo->prepare($consulta);

            // Asignar valores a los parámetros
            $stmt->bindParam(':id_partida', $id_partida, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la inserción se realizó correctamente
            if ($stmt->rowCount() > 0) {
                return [
                    'estado' => 'ok',
                    'descripcion' => 'inserción correcta'
                ];
            } else {
                return [
                    'estado' => 'error',
                    'descripcion' => 'error al insertar'
                ];
            }
        } catch (PDOException $e) {
            return [
                'estado' => 'error',
                'descripcion' => 'error en la inserción: ' . $e->getMessage()
            ];
        }
    }

    public function selectOne($table, $column, $searched)
    {
        // echo("<pre>");
        // var_dump($this);

        try {
            $statement = $this->pdo->prepare("select * from {$table} where `{$column}`='{$searched}'");
            $statement->execute();
            $resultado = $statement->fetchAll(PDO::FETCH_CLASS);

            if(!$resultado) {
                return false;
            }else{
                return get_object_vars($resultado[0]);
            }
        } catch (Exception $e) {
            $this->sendToLog($e);
            echo($e->getMessage());
            return $e->getCode();
        } 
    }

    public function existOne($table, $usuario, $contrasenia)
    {
        try {
            $statement = $this->pdo->prepare("SELECT 1 FROM `usuario` WHERE `id_usuario` = `:usuario` AND `contrasenia` = `:contrasenia`;");
            $statement->bindParam(':usuario', $usuario);
            $statement->bindParam(':contrasenia', $contrasenia);
            $statement->execute();
            $resultado = $statement->fetchAll(PDO::FETCH_CLASS);
            return $resultado[0];
        } catch (Exception $e) {
            $this->sendToLog($e);
            echo($e->getMessage());
            return $e->getCode();
        } 
    }

    public function insertEstado($parameters){

        $sql = "INSERT INTO `partida`(`id_usuario`, `estados_del_juego`) VALUES(:id_usuario, :estados_del_juego)";

        $array_consulta = [
            ':id_usuario' => $parameters['id_usuario'],
            ':estados_del_juego' => $parameters['estados_del_juego']
        ];

        $this->logger->info($sql);
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($array_consulta);

            if($statement->rowCount()>0){
                return ['registro_exitoso'=> true];
            }else{
                return ['registro_exitoso'=>false];
            }

        }catch (PDOException $e) {
            $this->sendToLog("{$e->getMessage()}");
            $this->logger->info($e->getMessage());
        }

    }

    public function buscarUsuario($datosBusqueda){

    try {
        $consultaSQL = "SELECT * FROM usuario WHERE ";

        // Inicializar el array para almacenar los valores que se vincularán a la consulta
        $valores = array();

        // Recorrer el arreglo asociativo de datos de búsqueda para construir la consulta
        foreach ($datosBusqueda as $campo => $valor) {
            // Agregar el campo y su valor a la consulta
            $consultaSQL .= "$campo = :$campo AND ";
            // Agregar el valor a la lista de valores para vincular
            $valores[":$campo"] = $valor;
        }

        // Eliminar el último "AND" de la consulta SQL
        $consultaSQL = rtrim($consultaSQL, "AND ");

        // Preparar la consulta
        $stmt = $this->pdo->prepare($consultaSQL);

        // Ejecutar la consulta con los valores vinculados
        $stmt->execute($valores);

        // Obtener los resultados (si los hay)
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar los resultados
        return $result[0];
    } catch (PDOException $e) {
        // Manejo de errores
        $this->sendToLog("Error: {$e->getMessage()}");
        return array(); // En caso de error, retornar un array vacío o false, según convenga
    } 

    }

    /**
     * Insert a record into a table.
     *
     * @param  string $table
     * @param  array  $parameters
     */
    public function insert($table, $parameters)
    {
        // Generar la sentencia SQL y ejecutarla
        $columns = implode(", ", array_keys($parameters));
        $values = ":" . implode(", :", array_keys($parameters));

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $inserted = $stmt->execute($parameters);

            if ($inserted) {
                    return ['estado' => 'ok',
                            'codigo' => 200,
                            'descripcion' => 'insercion exitosa'];
            } else {
                return ['estado' => 'error',
                        'codigo' => 2,
                        'descripcion' => 'fallo al insertar'];
            }
        } catch (PDOException $e) {
            $this->sendToLog("Error al insertar el registro: {$e->getMessage()}");
        }   
    }

public function guardarImagenes($imagenes, $id_partida, $id_usuario){
    $resultados = array();
    $carpeta_destino = realpath("imagenes_partida");

    foreach ($imagenes as $img_data) {
        $id_canva = $img_data['idCanvas'];
        $imageDataUrl = $img_data['imageDataUrl'];
        
        // Decodificar la URL de datos en datos binarios
        $datos_binarios = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageDataUrl));

        if ($datos_binarios !== false) {
            // Generar un nombre de archivo único
            $nombre_archivo = hash('sha256', $datos_binarios) . '.png';
            $ruta_destino = $carpeta_destino . DIRECTORY_SEPARATOR . $nombre_archivo;

            // Crear una imagen a partir de los datos binarios y guardarla
            if (file_put_contents($ruta_destino, $datos_binarios) !== false) {
                // Agregar el resultado a la lista de resultados
                $resultados[] = array(
                    'idCanvas' => $id_canva,
                    'nombreArchivo' => $nombre_archivo,
                );
            } else {
                // Error al guardar el archivo
                $resultados[] = array(
                    'idCanvas' => $id_canva,
                    'error' => 'Error al guardar el archivo'
                );
            }
        } else {
            // Error en la decodificación de datos binarios
            $resultados[] = array(
                'idCanvas' => $id_canva,
                'error' => 'Error en la decodificación de datos binarios'
            );
        }
    }

    return $resultados;
}



    public function updatePartida($data){

        $progreso = $this->pdo->quote($data['progreso']);

        // Construir la consulta SQL y prepararla        
        $consultaSQL = "UPDATE partida SET estado_partida = :estado_partida, progreso = $progreso, imagenes = :imagenes, puntaje = :puntaje";
            
        $consultaSQL .= " WHERE id_usuario = :id_usuario AND id_partida = :id_partida";

        $stmt = $this->pdo->prepare($consultaSQL);

        $imagesCanva = $data['imagesCanvas'];

        $pathUrlCanvas = json_encode($this->guardarImagenes($imagesCanva, $data['id_usuario'], $data['id_partida']), true);
        $data_puntaje = intval($data['puntaje']);

        $data['estado_partida'] = json_encode($data['estado_partida']);

        // Vincula los parámetros con los tipos correspondientes, excepto para progreso
        $stmt->bindParam(':estado_partida', $data['estado_partida'], PDO::PARAM_STR);
        $stmt->bindParam(':imagenes', $pathUrlCanvas, PDO::PARAM_STR);
        $stmt->bindParam(':puntaje', $data_puntaje, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $data['id_usuario'], PDO::PARAM_STR);
        $stmt->bindParam(':id_partida', $data['id_partida'], PDO::PARAM_INT);

        try {
            $stmt->execute();
            
            return [
                'estado' => 'ok',
                'descripcion' => 'Datos actualizados correctamente.'
            ];

        } catch (PDOException $e) {
            $this->sendToLog($e);
            return [
                'estado' => 'error',
                'descripcion' => "Error al actualizar los datos: {$e->getMessage()}"
            ];
        }
    }

    public function update($table, $parameters, $condition){
        $params = '';

        foreach($parameters as $key=>$value) {
            $params .= $key . " = '" . $value . "', "; 
         }
         
        $params = trim($params, ' '); // first trim last space
        $params = trim($params, ','); // then trim trailing and prefixing commas

        $sql = sprintf(
            'update %s set %s where %s',
            $table,
            $params,
            $condition
        );
        // echo("<pre>");
        // var_dump($sql);

        try {
            $statement = $this->pdo->prepare($sql);
            $result = $statement->execute();
            return $result;
        } catch (Exception $e) {
            $this->sendToLog($e);
            echo($e->getMessage());
            return $e->getCode();
        }          
    }  
    
    public function cargarImagenes($hashDataImage, $dataUser){
        $id_canva = 0;
        $dataImages = array();

        foreach ($hashDataImage as $dataImg) {
            $id_partida = $dataUser['id_partida'];
            $id_usuario = $dataUser['id_usuario'];
            // obtengo idCanva 
            $idCanva = "{$id_usuario}.{$id_partida}.{$id_canva}";
            // le hago el hash para saber el nombre del archivo
            if (isset($dataImg[$idCanva])) {
                $nombreArchivo = $dataImg[$idCanva] . ".png";
                // Ruta completa del archivo usando DIRECTORY_SEPARATOR
                $pathImg = realpath("imagenes_partida") . DIRECTORY_SEPARATOR  . $nombreArchivo;
    
                if (file_exists($pathImg)) {
                    // El archivo existe, carga y convierte en base64
                    $imgBase64 = trim(base64_encode(file_get_contents($pathImg)));
                    $imgBase64 = preg_replace('/dataimage\/pngbase64/', 'data:image/png;base64,', $imgBase64);
                    $dataImages["{$id_canva}"] = $imgBase64;
                } 
            } else {
              $this->sendToLog("la llave {$id_canva} no existe en el array asociativo");
            }
            // aumento en 1 el contador 
            $id_canva++;
        }
        // saliendo del bucle foreach, hago el return de la funcion 
        // devolviendo el array asociativo que luego ira al front
        // previo a ser convertido a json para poder ser enviado e interpretado 
        return $dataImages;
    }

    public function traerUltimoEstadoPartida($data) {
        try {
            $sql = "SELECT estado_partida, imagenes FROM partida WHERE id_usuario = :id_usuario AND id_partida = :id_partida";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $data['id_usuario'], PDO::PARAM_INT);
            $stmt->bindParam(':id_partida', $data['id_partida'], PDO::PARAM_INT);

            $stmt->execute();

            // Obtengo los valores de las columnas estado_partida e imagenes 
            $consulta_array = $stmt->fetch(PDO::FETCH_ASSOC);
            $estado_partida = json_decode($consulta_array['estado_partida'], true);
            $imagenes = $this->cargarImagenes(json_decode($consulta_array['imagenes'], true), [ 
                'id_usuario' => $data['id_usuario'],
                'id_partida' => $data['id_partida']
            ]);

            if ($estado_partida) {

                $arrayPiezasDesordenadas = json_encode($estado_partida['divPiezasDesordenadas']);
                $arrayPiezasCompletadas = json_encode($estado_partida['divPiezasCompletadas']);
                $aciertos = $estado_partida['aciertos'];
                $errores = $estado_partida['errores'];
                $tiempoTranscurrido = $estado_partida['tiempoTranscurrido'];

                return ['estado' => 'ok',
                        'dato' => [
                            'piezas' => $arrayPiezasDesordenadas,
                            'puzzle' => $arrayPiezasCompletadas,
                            'aciertos' => $aciertos, 
                            'errores' => $errores,
                            'tiempo' => $tiempoTranscurrido,
                            'id_partida' => $data['id_partida'],
                            'imagenes' => $imagenes,
                        ],
                        'descripcion' => 'metodoContinuarPartida: obtencion de datos exitoso'];
            } else {
                return ['estado' => 'error',
                        'descripcion' => 'metodoContinuarPartida: la sección divPiezasDesordenadas no se encontró'];
            }
        } catch (Exception $e) {
            return ['estado' => 'error',
                    'descripcion' => 'metodoContinuarPartida: ' . $e->getMessage()];
        }
    }

    private function sendToLog($e){
        if ($this->logger) {
            $this->logger->error('Error', ["Error" => $e]);
        }
    }

    /**
     * Limpia guiones - que puedan venir en los nombre de los parametros
     * ya que esto no funciona con PDO
     *
     * Ver: http://php.net/manual/en/pdo.prepared-statements.php#97162
     */
    private function cleanParameterName($parameters)
    {
        $cleaned_params = [];
        foreach ($parameters as $name => $value) {
            $cleaned_params[str_replace('-', '', $name)] = $value ;
        }
        return $cleaned_params;
    }

}
