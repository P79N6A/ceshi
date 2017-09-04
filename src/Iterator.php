<?php

function xrange($start, $limit, $step = 1) {
	for ($i = $start, $j = 0; $i <= $limit; $i += $step, $j++) {
		// 给予键值
		yield $j => $i;
	}
}

$xrange = xrange(1, 10, 2);
foreach ($xrange as $key => $value) {
	echo $key . ' => ' . $value . "\n";
}

//
//class MyIterator implements Iterator {
//
//	private $position = 0;
//
//	private $arr = [
//		'first',
//		'second',
//		'third',
//	];
//
//	/**
//	 * Return the current element
//	 *
//	 * @link  http://php.net/manual/en/iterator.current.php
//	 * @return mixed Can return any type.
//	 * @since 5.0.0
//	 */
//	public function current() {
//		var_dump(__METHOD__);
//
//		return $this->arr[$this->position];
//		// TODO: Implement current() method.
//	}
//
//	/**
//	 * Move forward to next element
//	 *
//	 * @link  http://php.net/manual/en/iterator.next.php
//	 * @return void Any returned value is ignored.
//	 * @since 5.0.0
//	 */
//	public function next() {
//		var_dump(__METHOD__);
//		++$this->position;
//		// TODO: Implement next() method.
//	}
//
//	/**
//	 * Return the key of the current element
//	 *
//	 * @link  http://php.net/manual/en/iterator.key.php
//	 * @return mixed scalar on success, or null on failure.
//	 * @since 5.0.0
//	 */
//	public function key() {
//		var_dump(__METHOD__);
//
//		return $this->position;
//		// TODO: Implement key() method.
//	}
//
//	/**
//	 * Checks if current position is valid
//	 *
//	 * @link  http://php.net/manual/en/iterator.valid.php
//	 * @return boolean The return value will be casted to boolean and then evaluated.
//	 *        Returns true on success or false on failure.
//	 * @since 5.0.0
//	 */
//	public function valid() {
//		var_dump(__METHOD__);
//
//		return isset($this->arr[$this->position]);
//		// TODO: Implement valid() method.
//	}
//
//	/**
//	 * Rewind the Iterator to the first element
//	 *
//	 * @link  http://php.net/manual/en/iterator.rewind.php
//	 * @return void Any returned value is ignored.
//	 * @since 5.0.0
//	 */
//	public function rewind() {
//		var_dump(__METHOD__);
//		$this->position = 0;
//		// TODO: Implement rewind() method.
//	}
//}
//
//$it = new MyIterator();
//
//foreach($it as $key => $val) {
//
//	echo "\n\r";
//	var_dump($key, $val);
//}