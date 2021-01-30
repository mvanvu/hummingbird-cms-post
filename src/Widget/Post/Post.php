<?php

namespace MaiVu\Hummingbird\Widget\Post;

use MaiVu\Hummingbird\Lib\Helper\Event;
use MaiVu\Hummingbird\Lib\Helper\Service;
use MaiVu\Hummingbird\Lib\Helper\UcmItem;
use MaiVu\Hummingbird\Lib\Mvc\Model\Post as PostModel;
use MaiVu\Hummingbird\Lib\Mvc\Model\PostCategory;
use MaiVu\Hummingbird\Lib\Widget;
use MaiVu\Php\Registry;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class Post extends Widget
{
	public function getContent(): ?string
	{
		$cid      = $this->widget->get('params.categoryIds', [], 'unique');
		$postsNum = $this->widget->get('params.listLimit', 0, 'uint');
		$sortBy   = $this->widget->get('params.sortBy', 'latest');
		$plugin   = Event::getPlugin('Cms', 'Post');

		if ($postsNum < 1)
		{
			$postsNum = $plugin->get('params.listLimit', 15);
		}

		if (empty($sortBy))
		{
			$sortBy = $plugin->get('params.sortBy', 'latest');
		}

		if (count($cid))
		{
			$bindIds = [];
			$nested  = new PostCategory;

			foreach ($cid as $id)
			{
				if ($tree = $nested->getTree((int) $id))
				{
					foreach ($tree as $node)
					{
						$bindIds[] = (int) $node->id;
					}
				}
			}

			if (empty($bindIds))
			{
				return null;
			}

			$queryBuilder = PostModel::query()
				->createBuilder()
				->from(['post' => PostModel::class])
				->where('post.parentId IN ({cid:array})', ['cid' => array_unique($bindIds)])
				->andWhere('post.state = :state: AND post.context = :context:', ['state' => 'P', 'context' => 'post'])
				->orderBy(UcmItem::parseSortBySql($sortBy));

			// Init renderer
			$renderer = $this->getRenderer();
			$partial  = 'Content/' . $this->getPartialId();

			if ('BlogList' === $this->widget->get('params.displayLayout', 'FlashNews'))
			{
				$paginator = new Paginator(
					[
						'builder' => $queryBuilder,
						'limit'   => $postsNum,
						'page'    => Registry::request()->get('request.page', 1, 'uint'),
					]
				);

				$paginate = $paginator->paginate();

				if ($paginate->getTotalItems())
				{
					return $renderer->getPartial(
						$partial,
						[
							'posts'      => $paginate->getItems(),
							'pagination' => Service::view()->getPartial('Pagination/Pagination',
								[
									'paginator' => $paginator,
								]
							),
						]
					);
				}
			}
			else
			{
				$posts = $queryBuilder->limit($postsNum, 0)->getQuery()->execute();

				if ($posts->count())
				{
					return $renderer->getPartial($partial, ['posts' => $posts]);
				}
			}
		}

		return null;
	}
}