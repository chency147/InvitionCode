<?php
class InvitionCodeUtil {
	// 给定字符序列
	// 可以更换其中的顺序和字母，但是不可以包含数字零('0')
	const CHARS = [
		'G', 'W', 'E', '7', 'I', 'S', '2', '6', 'U', 'X', '9', 'R',
		'8', 'P', '5', 'A', 'K', '3', 'M', 'J', 'Z', 'F', 'C', '4', 
		'L', 'Y', 'V', 'T', 'N', 'D', 'B', 'Q', 'H'
	];
	// 设备码间隔码
	const DIVIDER = '0';
	// 最短设备码
	const CODE_MIN_LENGTH = 6;

	/*
	 * 构造函数
	 */
	public function __construct() {
		$this -> charsLen = count(self::CHARS);
	}

	/*
	 * ID转化为设备码
	 */
	public function id2Code($id) {
		$buf = '';
		// 最大下标
		$posMax = $this -> charsLen - 1;

		// 将10进制的id转化为33进制的邀请码
		while(((int)($id / $this -> charsLen)) > 0) {
			$ind = $id % $this -> charsLen;
			$buf .= self::CHARS[$ind];
			$id = (int) ($id / $this -> charsLen);
		}
		$buf .= self::CHARS[(int) $id % $this -> charsLen];
		// 反转buf字符串
		$buf = strrev($buf);
		// 补充长度
		$fixLen = self::CODE_MIN_LENGTH - mb_strlen($buf, 'UTF-8');
		if ($fixLen > 0) {
			$buf .= self::DIVIDER;
			for ($i = 0; $i < $fixLen - 1; $i ++) {
				// 从字符序列中随机取出字符进行填充
				$buf .= self::CHARS[rand(0, $posMax)];
			}
		}
		// echo $buf.'<br />';
		return $buf;
	}
	/*
	 * 设备码转化为ID
	 */
	public function code2ID($code) {
		$codeLen = mb_strlen($code, 'UTF-8');
		$id = 0;
		// 33进制转10进制
		for ($i = 0; $i < $codeLen; $i++) {
			if ($code[$i] === self::DIVIDER) {
				break;
			}
			$ind = 0;
			for ($j = 0; $j < $this -> charsLen; $j ++) {
				if ($code[$i] === self::CHARS[$j]) {
					$ind = $j;
					break;
				}
			}
			if ($i > 0) {
				// echo $ind.'    '.$id.'<br />';
				$id = $id * $this -> charsLen + $ind;
			} else {
				$id = $ind;
			}
		}
		// echo $code.'<br />';
		// echo $id;
		return $id;
	}
}
$invitionCodeUtil = new InvitionCodeUtil();
echo $invitionCodeUtil -> id2Code(534213);
