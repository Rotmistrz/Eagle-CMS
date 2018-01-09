<?php

interface Orderable {
    const INITIAL_ORDER = 0;
    const ORDER_STEP = 10;

    const ASC = "ASC";
    const DESC = "DESC";

	public function getEarlierOne();
	public function getLaterOne();
    public function getOrder();
    public static function getFollowingOrder($type);
}

?>