<?php
/**
 * Nextcloud - TextTemplates
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier
 * @copyright Julien Veyssier 2023
 */

namespace OCA\TextTemplates\Service;

use OCA\TextTemplates\AppInfo\Application;
use OCP\Http\Client\IClient;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\L10N\IFactory;
use Psr\Log\LoggerInterface;
use OCP\Http\Client\IClientService;

class TemplateService {
	private LoggerInterface $logger;
	private IL10N $l10n;
	private IConfig $config;
	private IURLGenerator $urlGenerator;
	private IClient $client;
	private IFactory $l10nFactory;

	public function __construct (string $appName,
								LoggerInterface $logger,
								IL10N $l10n,
								IConfig $config,
								IURLGenerator $urlGenerator,
								IFactory $l10nFactory,
								IClientService $clientService) {
		$this->client = $clientService->newClient();
		$this->logger = $logger;
		$this->l10n = $l10n;
		$this->config = $config;
		$this->urlGenerator = $urlGenerator;
		$this->l10nFactory = $l10nFactory;
	}
}
