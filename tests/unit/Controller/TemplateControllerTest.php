<?php
/**
 * Nextcloud - TextTemplates
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

namespace OCA\TextTemplates\Controller;

use OCA\TextTemplates\Db\Template;
use OCA\TextTemplates\Db\TemplateMapper;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;
use PHPUnit\Framework\TestCase;

class TemplateControllerTest extends TestCase {

	private $appName = 'text_templates';
	private $request;
	private $templateMapper;
	private $userId = 'testuser';

	protected function setUp(): void {
		parent::setUp();

		$this->request = $this->createMock(IRequest::class);
		$this->templateMapper = $this->createMock(TemplateMapper::class);
	}

	private function initTemplate(int $id, string $name, string $content, ?string $userId): Template {
		$template = new Template();
		$template->setId($id);
		$template->setName($name);
		$template->setContent($content);
		$template->setUserId($userId);
		return $template;
	}

	public function testGetUserTemplates(): void {
		$userTemplates = [
			$this->initTemplate(1, 'template1', 'content1', 'testuser'),
			$this->initTemplate(2, 'template2', 'content2', 'testuser')
		];
		$adminTemplates = [
			$this->initTemplate(3, 'template3', 'content3', null),
			$this->initTemplate(4, 'template4', 'content4', null)
		];

		$this->templateMapper->expects($this->exactly(2))
			->method('getTemplatesOfUser')
			->withConsecutive(
				[$this->equalTo($this->userId)],
				[$this->equalTo(null)]
			)
			->willReturnOnConsecutiveCalls(
				$userTemplates,
				$adminTemplates
			);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->getUserTemplates(true);
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals(array_merge($userTemplates, $adminTemplates), $response->getData());
	}

	public function testAddUserTemplate(): void {
		$name = 'newtemplate';
		$content = 'newcontent';
		$template = $this->initTemplate(1, $name, $content, $this->userId);
		
		$this->templateMapper->expects($this->once())
			->method('createTemplate')
			->with(
				$this->equalTo($this->userId),
				$this->equalTo($name),
				$this->equalTo($content)
			)
			->willReturn($template);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->addUserTemplate($name, $content);
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals($template, $response->getData());
	}

	public function testEditUserTemplate(): void {
		$id = 1;
		$name = 'newname';
		$content = 'newcontent';
		$template = $this->initTemplate($id, $name, $content, $this->userId);
		
		$this->templateMapper->expects($this->once())
			->method('updateTemplate')
			->with(
				$this->equalTo($id),
				$this->equalTo($this->userId),
				$this->equalTo($name),
				$this->equalTo($content)
			)
			->willReturn($template);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->editUserTemplate($id, $name, $content);
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals($template, $response->getData());
	}

	public function testDeleteUserTemplate(): void {
		$id = 1;
		$template = $this->initTemplate($id, 'template1', 'content1', $this->userId);

		$this->templateMapper->expects($this->once())
			->method('deleteTemplate')
			->with($this->equalTo($id), $this->equalTo($this->userId))
			->willReturn($template);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->deleteUserTemplate($id);
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals($template, $response->getData());
	}

	public function testGetAdminTemplates(): void {
		$template1 = $this->initTemplate(3, 'template3', 'content3', null);
		$template2 = $this->initTemplate(4, 'template4', 'content4', null);

		$adminTemplates = [
			$template1,
			$template2
		];
		$this->templateMapper->expects($this->once())
			->method('getTemplatesOfUser')
			->with($this->equalTo(null))
			->willReturn($adminTemplates);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->getAdminTemplates();
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals($adminTemplates, $response->getData());
	}

	public function testAddAdminTemplate(): void {
		$name = 'newtemplate';
		$content = 'newcontent';

		$template = $this->initTemplate(1, $name, $content, null);

		$this->templateMapper->expects($this->once())
			->method('createTemplate')
			->with(
				$this->equalTo(null),
				$this->equalTo($name),
				$this->equalTo($content)
			)
			->willReturn($template);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->addAdminTemplate($name, $content);
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals($template, $response->getData());
	}

	public function testEditAdminTemplate(): void {
		$id = 1;
		$name = 'newname';
		$content = 'newcontent';

		$template = $this->initTemplate($id, 'template1', 'content1', null);
		
		$this->templateMapper->expects($this->once())
			->method('updateTemplate')
			->with(
				$this->equalTo($id),
				$this->equalTo(null),
				$this->equalTo($name),
				$this->equalTo($content)
			)
			->willReturn($template);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->editAdminTemplate($id, $name, $content);
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals($template, $response->getData());
	}

	public function testDeleteAdminTemplate(): void {
		$id = 1;
		$template = $this->initTemplate($id, 'template1', 'content1', null);
		
		$this->templateMapper->expects($this->once())
			->method('deleteTemplate')
			->with($this->equalTo($id), $this->equalTo(null))
			->willReturn($template);

		$controller = new TemplateController(
			$this->appName,
			$this->request,
			$this->templateMapper,
			$this->userId
		);

		$response = $controller->deleteAdminTemplate($id);
		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertEquals($template, $response->getData());
	}
}
