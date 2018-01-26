<?php
class Database{
    public $isConn;
    protected $datab;

    // veri tabanına bağlama
    public function __construct($username, $password, $host, $dbname, $options = []){
        $this->isConn = TRUE;
        try {
            $this->datab = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
            $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

    }

    // eğer veritabanına bağlanmazsa
    public function Disconnect(){
        $this->datab = NULL;
        $this->isConn = FALSE;
    }
    // satır getir
    public function getRow($query, $params = []){
        try {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    // tum satırları getir
    public function getRows($query, $params = []){
        try {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    // satır ekleme
    public function insertRow($query, $params = []){
        try {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    // satır guncelle
    public function updateRow($query, $params = []){
        try{
            $this->insertRow($query, $params);
            return true;
        }catch (Exception $ex){
            return $ex->getMessage();
        }

    }
    // satır sil
    public function deleteRow($query, $params = []){
        try{
            $this->insertRow($query, $params);
            return true;
        }catch (Exception $ex){
            return $ex->getMessage();
        }
    }
}
?>