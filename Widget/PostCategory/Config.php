<?php

return [
	'name'        => 'PostCategory',
	'title'       => 'widget-post-category-title',
	'description' => 'widget-post-category-desc',
	'version'     => '1.0.0',
	'author'      => 'Mai Vu',
	'authorEmail' => 'mvanvu@gmail.com',
	'authorUrl'   => 'https://github.com/mvanvu',
	'updateUrl'   => null,
	'params'      => [
		[
			'name'     => 'categoryId',
			'type'     => 'CmsModalUcmItem',
			'context'  => 'post-category',
			'filters'  => ['uint'],
			'rules'    => ['ValidUcmItem'],
			'required' => true,
		],
		[
			'name'    => 'showPosts',
			'type'    => 'Switcher',
			'label'   => 'show-posts',
			'value'   => 'Y',
			'filters' => ['yesNo'],
		],
		[
			'name'    => 'sortBy',
			'type'    => 'Select',
			'label'   => 'sort-by',
			'class'   => 'uk-select',
			'options' => [
				''          => 'use-global-config',
				'latest'    => 'order-latest',
				'random'    => 'order-random',
				'titleAsc'  => 'order-title-asc',
				'titleDesc' => 'order-title-desc',
			],
			'rules'   => ['Options'],
			'showOn'  => 'showPosts:Y',
			'value'   => '',
		],
	],
];