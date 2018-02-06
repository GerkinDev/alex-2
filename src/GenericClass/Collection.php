<?php

namespace App\GenericClass;

use Doctrine\Common\Collections\ArrayCollection;

class Collection extends ArrayCollection implements \JsonSerializable{
	static public function fromCountable(\Countable $col): self{
		dump($col);
		dump(count($col));
	}
	static public function fromIterable(\IteratorAggregate $col): self{
		return new static(iterator_to_array($col->getIterator()));
	}

	public function set($key, $value){
		parent::set($key, $value);

		return $this;
	}

	public function map(\Closure $iterator): self{
		$arr = $this->toArray();
		$newCol = new static($arr);
		foreach($arr as $index => $element){
			$newCol->set($index, $iterator($element, $index, $arr));
		}
		return $newCol;
	}

	public function reduce(\Closure $iterator, $initial): self{
		$arr = $this->toArray();
		foreach($arr as $index => $element){
			$initial = $iterator($initial, $element, $index, $arr);
		}
		return $initial;
	}

	public function addMultiple(array $newValues, bool $noDuplicate = false): self{
		if($noDuplicate){
			foreach($newValues as $newValue){
				if(!$this->contains($newValue)){
					$this->add($newValue);
				}
			}
		} else {
			foreach($newValues as $newValue){
				$this->add($newValue);
			}
		}
		return $this;
	}


	public function jsonSerialize()
	{
		return $this->toArray();
	}
}