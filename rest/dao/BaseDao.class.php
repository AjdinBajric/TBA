<?php
require_once __DIR__ . '/../Config.class.php';

class BaseDao
{
    private $conn;
    private $table_name;

    public function __construct($table_name)
    {
        $this->table_name = $table_name;
        $servername = Config::DB_HOST();
        $username = Config::DB_USERNAME();
        $password = Config::DB_PASSWORD();
        $schema = Config::DB_SCHEMA();
        $port = Config::DB_PORT();

//        $this->conn = new PDO("mysql:host=127.0.0.1;port=3306;dbname=tba", "root", "");
        $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$schema", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function get_all()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_by_id($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return @reset($result);
    }

    public function delete_element($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    protected function add($entity)
    {
        $query = "INSERT INTO " . $this->table_name . " (";
        foreach (array_keys($entity) as $column) {
            $query .= $column . ", ";
        }
        $query = rtrim($query, ", ");
        $query .= ") VALUES (";
        foreach ($entity as $value) {
            $query .= "?, ";
        }
        $query = rtrim($query, ", ");
        $query .= ")";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(array_values($entity));
        $entity['id'] = $this->conn->lastInsertId();
        return $entity;
    }

    protected function update($id, $entity, $id_column = "id")
    {
        $query = "UPDATE " . $this->table_name . " SET ";
        foreach (array_keys($entity) as $name) {
            $query .= $name . "= :" . $name . ", ";
        }
        $query = rtrim($query, ", ");
        $query .= " WHERE ${id_column} = :id";

        $entity['id'] = $id;
        $stmt = $this->conn->prepare($query);
        $stmt->execute($entity);
        return $entity;
    }


    protected function query($query, $params)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params)
    {
        $results = $this->query($query, $params);
        return reset($results);
    }

    public function add_element($entity)
    {
        return $this->add($entity);
    }

    public function update_element($id, $entity)
    {
        return $this->update($id, $entity);
    }

    public function query_without_params($query)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>