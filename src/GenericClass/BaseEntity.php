<?php

namespace App\GenericClass;

class BaseEntity{
	static protected function ensureArrayCollection($col){
		if(is_array($col)){
			return new ArrayCollection($col);
		}
		return $col;
	}
	protected function addToCol(string $property, $element) {
		if (!$this->$property->contains($element)) {
			$this->$property->add($element);
		}

		return $this;
	}
}