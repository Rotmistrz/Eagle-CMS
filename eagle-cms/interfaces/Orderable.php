<?php

interface Orderable {
    const INITIAL_ORDER = 0;
    const ORDER_STEP = 10;

	public function getEarlierOne();
	public function getLaterOne();
    public function getOrder();
    public static function getFollowingOrder($type);
}

?>