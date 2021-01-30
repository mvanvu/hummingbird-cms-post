<?php

namespace MaiVu\Hummingbird\Widget\PostCategory;

use MaiVu\Hummingbird\Lib\Helper\Database;
use MaiVu\Hummingbird\Lib\Helper\Service;
use MaiVu\Hummingbird\Lib\Helper\UcmItem;
use MaiVu\Hummingbird\Lib\Mvc\Model\PostCategory as CategoryModel;
use MaiVu\Hummingbird\Lib\Widget;
use Phalcon\Db\Enum;

class PostCategory extends Widget
{
	public function getContent(): ?string
	{
		if (($cid = $this->widget['params.categoryId']) && ($category = CategoryModel::findFirst($cid)))
		{
			return $this->getRenderer()->getPartial(
				'Content/Category',
				[
					'category' => $category,
				]
			);
		}

		return null;
	}

	public function getPosts(CategoryModel $category)
	{
		if (!$sortBy = $this->widget['params.sortBy'])
		{
			$sortBy = UcmItem::parseCategoryParams($category)->get('sortBy');
		}

		return Service::db()
			->fetchAll('SELECT p.id, p.title, p.route FROM ' . Database::table('ucm_items') . ' AS p WHERE p.parentId = :categoryId AND p.context = :context AND p.state = :published ORDER BY ' . UcmItem::parseSortBySql($sortBy, 'p'),
				Enum::FETCH_OBJ,
				[
					'categoryId' => $category->id,
					'context'    => 'post',
					'published'  => 'P',
				]
			);
	}
}