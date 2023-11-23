<?php
/**
 * Nextcloud - TextTemplates
 *
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

namespace OCA\TextTemplates\AppInfo;

use OCA\TextTemplates\Listener\TextTemplatesReferenceListener;
use OCA\TextTemplates\Reference\TextTemplatesReferenceProvider;
use OCP\Collaboration\Reference\RenderReferenceEvent;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;

class Application extends App implements IBootstrap {

	public const APP_ID = 'text_templates';

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerReferenceProvider(TextTemplatesReferenceProvider::class);
		$context->registerEventListener(RenderReferenceEvent::class, TextTemplatesReferenceListener::class);
	}

	public function boot(IBootContext $context): void {
	}
}

