<?php

namespace OCA\TextTemplates\Tests;

use OCA\TextTemplates\AppInfo\Application;

class TemplateServiceTest extends \PHPUnit\Framework\TestCase {

	public function testDummy() {
		$app = new Application();
		$this->assertEquals('text_templates', $app::APP_ID);
	}
}
