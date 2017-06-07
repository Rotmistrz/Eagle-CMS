<?php

class FormManager extends Form {
	private $items = [];

	public function addItem(FormItem $item) {
		$this->items[] = $item;
	}

	public function addCategories($type, $current) {
		$categoriesFactory = new CategoriesCollectionFactory();
		$collection = $categoriesFactory->load($type);
		$categories = $collection->getAsKeyValueArray(Language::PL);
		$checkboxesGroup = new CheckboxesGroup('category', 'Kategoria');

		foreach($categories as $value => $description) {
			$checkbox = new Checkbox('category', $value, $description);
			
			if(in_array($value, $current)) {
				$checkbox->setChecked(true);
			}

			$checkboxesGroup->addValue($checkbox);
		}

		$this->addItem($checkboxesGroup);
	}

	public function addInput($id, $title, $value) {
		$input = new Input();
		$input->id = $id;
		$input->type = 'text';
		$input->value = $value;

		$label = new Label();
		$label->title = $title;
		$label->setField($input);

		$this->addItem($label);
	}

	public function addInputPassword($id, $title, $value) {
		$input = new Input();
		$input->id = $id;
		$input->type = 'password';
		$input->value = $value;

		$label = new Label();
		$label->title = $title;
		$label->setField($input);

		$this->addItem($label);
	}

	public function addInputHidden($id, $value) {
		$input = new Input();
		$input->id = $id;
		$input->type = 'hidden';
		$input->value = $value;

		$this->addItem($input);
	}

	public function addTextarea($id, $title, $value) {
		$textarea = new Textarea();
		$textarea->id = $id;
		$textarea->value = $value;

		$label = new Label();
		$label->title = $title;
		$label->setField($textarea);

		$this->addItem($label);
	}

	public function addFileField($id, $title) {
		$filefield = new FileField();
		$filefield->id = $id;

		$label = new Label();
		$label->title = $title;
		$label->setField($filefield);

		$this->addItem($label);
	}

	public function addSelect($title, Select $select) {
		$label = new Label();
		$label->title = $title;
		$label->setField($select);

		$this->addItem($label);
	}

	public function addButton($value) {
		$button = new Button();
		$button->type = 'submit';
		$button->value = $value;

		$this->addItem($button);
	}

	public function get() {
		$content = '';

		foreach($this->items as $item) {
			$content .= $item->get($this->twig);
		}

		return $this->twig->render('form.tpl', ['id' => $this->id, 'class' => $this->class, 'action' => $this->action, 'method' => $this->method, 'content' => $content]);
	}
}

?>