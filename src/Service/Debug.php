<?php

namespace App\Service;

class Debug {
	private static $instance;
	private $serializer;

	public function __construct(\JMS\Serializer\SerializerInterface $serializer){
		$this->serializer = $serializer;
		self::$instance = $this;
	}

	public function dumpModel($model){
		echo '<pre>';
		echo $this->serializer->serialize($model, 'json');
		echo '</pre>';
	}

	public function dumpCollection($collection){
		$datas = [];
		foreach($collection as $key => $model){
			$datas[$key] = json_decode($this->serializer->serialize($model, 'json'), true);
		}

		echo '<pre>';
		echo var_dump($datas);
		echo '</pre>';
	}

	public function getInstance(){
		return self::$instance;
	}
}