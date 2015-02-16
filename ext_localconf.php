<?php
if (TYPO3_MODE === 'BE') {

	$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
		'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher'
	);
	$signalSlotDispatcher->connect(
		'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
		'hasInstalledExtensions',
		'ThemesTeam\\ThemesDistribution\\Composer\\InstallerScripts',
		'postInstallExtension'
	);

}
?>