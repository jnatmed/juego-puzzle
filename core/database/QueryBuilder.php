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
        // SELECT * FROM `escuela`.`alumno` WHERE `nombre_alumno` = 'dante natello medina'
        // $statement = $this->pdo->prepare("select * from {$table} where `{$column}`='{$searched}'");
        // $statement->execute();
        // return $statement->fetchAll(PDO::FETCH_CLASS);

        // echo("entre");
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

    public function insertEstado($parameters){

        $sql = "INSERT INTO `partida`(`id_usuario`, `estados_del_juego`) VALUES(:id_usuario, :estados_del_juego)";

        $array_consulta = [
            ':id_usuario' => $parameters['id_usuario'],
            ':estados_del_juego' => $parameters['estados_del_juego']
        ];

        $this->logger->info("2) {$sql}", ['sentencia sql', 'QueryBuilder']);

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($array_consulta);

            if($statement->rowCount()>0){
                return ['registro_exitoso'=> 'insertado !'];
            }else{
                return ['registro_exitoso'=> 'no insertado...' ];
            }

        }catch (PDOException $e) {
            echo $e->getMessage();
            $this->logger->info("2-error) {$e->getMessage()}");
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
