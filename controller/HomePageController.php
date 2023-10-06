<?php
require_once "model/Recette.php";

class HomePageController {
    private $model;

    public function __construct($db) {
        $this->model = new Recette($db);
    }
    
    public function showHome() {
        $stmt = $this->model->getAllRecettes();
        $recettes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recettes[] = $row;
        }
        include "view/home.php";
    }
}
