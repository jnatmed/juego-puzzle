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
        $statement = $this->pdo->prepare("select * from {$table}");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function selectOne($table, $column, $searched)
    {
        // echo("<pre>");
        // var_dump($this);

        try {
            $statement = $this->pdo->prepare("select * from {$table} where `{$column}`='{$searched}'");
            $statement->execute();
            $resultado = $statement->fetchAll(PDO::FETCH_CLASS);
            return !empty($resultado) ? ['resultado'=>true, 'datos'=> get_object_vars($resultado[0])] : ['resultado'=>false];
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
            return !empty($resultado) ? ['resultado'=>true, 'datos'=> get_object_vars($resultado[0])] : ['resultado'=>false];
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
            echo $e->getMessage();
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
        echo "Error: " . $e->getMessage();
        return array(); // En caso de error, retornar un array vacío o false, según convenga
    } finally {
        // Cerrar la conexión a la base de datos
        $this->pdo = null;
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

        // echo("<pre>");
        // var_dump($parameters);
        $aux = array_values($parameters);
        // echo("<pre>");
        // var_dump($aux);
        if ($aux['3']=='NULL'){
            $aux = array_slice($aux,0,3);
            $values = implode("','", $aux);
            $values = "'".$values."',"."NULL";  
            // echo($values);
        }else{
            $values = implode("','", array_values($parameters));
            $values = "'".$values."'";    
            // echo("<pre>");
            // var_dump($values);
        }
        $parameters = $this->cleanParameterName($parameters);
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            $values
        );
        // echo("<pre>");
        // var_dump($sql);

        try {
            $statement = $this->pdo->prepare($sql);
            $result = $statement->execute();
            return $result;
        } catch (Exception $e) {
            $this->sendToLog($e);
            // echo($e->getMessage());
            return $e->getCode();
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

    private function 
    sendToLog(Exception $e)
    {
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
