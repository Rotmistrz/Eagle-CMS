<?php

abstract class Component extends LanguagableElement {
	protected $id;
	public $order;
	public $visible;

	abstract public function save();
	abstract public function delete();

	public function setVisible($visible) {
		if(is_bool($visible)) {
			$this->visible = (bool) $visible;
		} else {
			throw new Exception("Item::setVisible() must receive boolean value.");
		}
	}

	public function getVisible() {
		return $this->visible;
	}

	public function setOrder($order) {
		if(is_number($order)) {
			$this->order = $order;
		} else {
			throw new Exception("Item::setOrder() must receive integer value.");
		}

		return $this;
	}

	public function getOrder() {
		return $this->order;
	}

	public function getId() {
		return $this->id;
	}
}

?>