<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2023, Julien Veyssier <julien-nc@posteo.net>
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\TextTemplates\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @template-extends QBMapper<Template>
 */
class TemplateMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'text_templates_t', Template::class);
	}

	/**
	 * @param int $id
	 * @return Template
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 */
	public function getTemplate(int $id): Template {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);

		return $this->findEntity($qb);
	}

	/**
	 * @param int $id
	 * @param string|null $userId
	 * @return Template
	 * @throws DoesNotExistException
	 * @throws Exception
	 * @throws MultipleObjectsReturnedException
	 */
	public function getTemplateOfUser(int $id, ?string $userId): Template {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);
		if ($userId === null) {
			$qb->andWhere(
				$qb->expr()->isNull('user_id')
			);
		} else {
			$qb->andWhere(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);
		}

		return $this->findEntity($qb);
	}

	/**
	 * @param string|null $userId
	 * @return array|Entity
	 * @throws Exception
	 */
	public function getTemplatesOfUser(?string $userId) {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName());
		if ($userId === null) {
			$qb->andWhere(
				$qb->expr()->isNull('user_id')
			);
		} else {
			$qb->andWhere(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);
		}

		return $this->findEntities($qb);
	}

	/**
	 * @param string|null $userId
	 * @param string $name
	 * @param string $content
	 * @return Template
	 * @throws Exception
	 */
	public function createTemplate(?string $userId, string $name, string $content): Template {
		$template = new Template();
		if ($userId !== null) {
			$template->setUserId($userId);
		}
		$template->setName($name);
		$template->setContent($content);
		return $this->insert($template);
	}

	/**
	 * @param int $id
	 * @param string|null $userId
	 * @param string|null $name
	 * @param string|null $content
	 * @return mixed|Entity
	 * @throws Exception
	 */
	public function updateTemplate(int $id, ?string $userId, ?string $name = null, ?string $content = null) {
		if ($name === null && $content === null) {
			return null;
		}
		try {
			$template = $this->getTemplateOfUser($id, $userId);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			return null;
		}
		if ($name !== null) {
			$template->setName($name);
		}
		if ($content !== null) {
			$template->setContent($content);
		}
		return $this->update($template);
	}

	/**
	 * @param int $id
	 * @param string|null $userId
	 * @return Template|null
	 * @throws Exception
	 */
	public function deleteTemplate(int $id, ?string $userId): ?Template {
		try {
			$template = $this->getTemplateOfUser($id, $userId);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			return null;
		}

		return $this->delete($template);
	}
}
