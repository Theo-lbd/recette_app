<?php

$env = parse_ini_string(file_get_contents(__DIR__ . '/../.env'), false, INI_SCANNER_RAW);
$_ENV = array_merge($_ENV, $env);
/**
 * Classe Database pour gérer la connexion à la base de données.
 */
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    /**
     * Constructeur qui initialise les paramètres de connexion à partir d'un fichier de configuration.
     */
    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
    }

    /**
     * Établit la connexion à la base de données et retourne l'instance de connexion.
     * @return PDO Instance de connexion à la base de données.
     */
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}