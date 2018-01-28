<?php

namespace App\Test\GenericClass;

class Model{
    private $id;
    public function __construct($id){
        $this->id = $id;
    }

    function getId(){
        return $this->id;
    }

    function getAttrsFactors(){
        return ['main' => 1];
    }
}
class Material{
    private $id;
    public function __construct($id){
        $this->id = $id;
    }

    function getId(){
        return $this->id;
    }
}

$materials = [
    new Material(1),
    new Material(2),
];
$models = [
    new Model(1),
    new Model(2),
];

$findModels = function($ids){
    global $models;

    if(!is_array($ids)){
        $ids = [$ids];
    }

    $return = [];
    foreach($models as $model){
        if(in_array($model->getId(), $ids)){
            $return[] = $model;
        }
    }
    return $return;
};

$findMaterials = function($ids){
    global $materials;

    if(!is_array($ids)){
        $ids = [$ids];
    }

    $return = [];
    foreach($materials as $material){
        if(in_array($material->getId(), $ids)){
            $return[] = $material;
        }
    }
    return $return;
};