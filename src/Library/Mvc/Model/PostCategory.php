<?php

namespace MaiVu\Hummingbird\Lib\Mvc\Model;

class PostCategory extends CategoryBase
{
	/**
	 * @var string
	 */
	public $context = 'post-category';

	/**
	 * @var bool
	 */
	public $hasRoute = true;

	/**
	 * @var string
	 */
	public $permitPkgName = 'post';
}