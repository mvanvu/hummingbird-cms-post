<?php

namespace App\Mvc\Model;

class Post extends UcmItem
{
	public $context = 'post';
	public $hasRoute = true;

	public function getParent()
	{
		return $this->getRelated('category');
	}

	public function initialize()
	{
		parent::initialize();
		$this->belongsTo('parentId', PostCategory::class, 'id',
			[
				'alias'    => 'category',
				'reusable' => true,
				'params'   => [
					'order' => 'ordering ASC',
				],
			]
		);

		$this->hasManyToMany('id', UcmItemMap::class, 'itemId1', 'itemId2', Tag::class, 'id',
			[
				'alias'    => 'tags',
				'reusable' => true,
				'params'   => [
					'conditions' => UcmItemMap::class . '.context = :context:',
					'bind'       => [
						'context' => 'tag',
					],
				],
			]
		);
	}
}
