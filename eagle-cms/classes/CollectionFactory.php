<?php

class CollectionFactory {
    const NO_OFFSET = -1;
    const NO_LIMIT = -1;

    protected $offset;
    protected $limit;

    protected $loadHiddenItems;
    protected $orderType;

    public function __construct() {
        $this->loadHiddenItems = false;
        $this->orderType = Orderable::ASC;

        $this->resetOffset();
        $this->resetLimit();
    }

    public function setLoadHiddenItems($bool) {
        $this->loadHiddenItems = (bool) $bool;

        return $this;
    }

    public function setOffset($offset) {
        $this->offset = $offset;

        return $this;
    }

    public function resetOffset() {
        $this->offset = self::NO_OFFSET;

        return $this;
    }

    public function setLimit($limit) {
        $this->limit = $limit;

        return $this;
    }

    public function resetLimit() {
        $this->limit = self::NO_LIMIT;
    }

    public function setDescendingOrder() {
        $this->orderType = Orderable::DESC;

        return $this;
    }

    public function setAscendingOrder() {
        $this->orderType = Orderable::ASC;

        return $this;
    }
}

?>