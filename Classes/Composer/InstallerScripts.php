<?php
namespace ThemesTeam\ThemesDistribution\Composer;

use Composer\Script\CommandEvent;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class InstallerScripts {
	
	/**
	 * Called from composer
	 *
	 * @param CommandEvent $event
	 * @return void
	 */
	static public function postUpdateAndInstall(CommandEvent $event) {
		// Some actions for composer based installation
	}

	/**
	 * Called from TYPO3 CMS extension manager
	 * @param string $extension
	 * @return void
	 */
	static public function postInstallExtension($extension) {
		
		if ($extension == 'themes_distribution') {

			$messageQueueByIdentifier = 'core.template.flashMessages';
			if(VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 7000000) {
				$messageQueueByIdentifier = 'extbase.flashmessages.tx_extensionmanager_tools_extensionmanagerextensionmanager';
			}

			/** @var $flashMessageService \TYPO3\CMS\Core\Messaging\FlashMessageService */
			$flashMessageService = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessageService');
			/** @var $flashMessageQueue \TYPO3\CMS\Core\Messaging\FlashMessageQueue */
			$flashMessageQueue = $flashMessageService->getMessageQueueByIdentifier($messageQueueByIdentifier);

			$flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
				'Have fun with TYPO3-THEMES',
				'Installation completed!',
				FlashMessage::OK,
				TRUE
			);
			$flashMessageQueue->enqueue($flashMessage);
			
			// Apache!?
			if (substr($_SERVER['SERVER_SOFTWARE'], 0, 6) === 'Apache') {

				$htaccessFile = GeneralUtility::getFileAbsFileName(".htaccess");
				if (!file_exists($htaccessFile)) {

					$htaccessDefaultFile = GeneralUtility::getFileAbsFileName("_.htaccess");
					if (file_exists($htaccessDefaultFile)) {

						$htaccessContent = GeneralUtility::getUrl($htaccessDefaultFile);
						if (GeneralUtility::writeFile($htaccessFile, $htaccessContent, TRUE)) {
							
							$flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
								'Default .htaccess file created',
								'Yeah',
								FlashMessage::OK,
								TRUE
							);
							$flashMessageQueue->enqueue($flashMessage);
							
						}
						
					}
				}
				else {

					$flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
						'Apache .htaccess file already exists',
						'Notice',
						FlashMessage::NOTICE,
						TRUE
					);
					$flashMessageQueue->enqueue($flashMessage);
					
				}
				
			}

		}
	
	}

}
?>