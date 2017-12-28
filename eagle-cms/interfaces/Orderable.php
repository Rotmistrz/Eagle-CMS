<?php

interface Orderable {
    const ORDER_STEP = 10;

	public function getEarlierOne();
	public function getLaterOne();
}

?>