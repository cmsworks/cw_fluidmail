<?php
namespace CmsWorks\CwFluidmail\Mail;


/***************************************************************
*
*  Copyright notice
*
*  (c) 2014 Arjan de Pooter <arjan@cmsworks.nl>, CMS Works BV
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
use TYPO3\CMS\Core\Utility\GeneralUtility as Util;

class FluidMailFactory implements FluidMailFactoryInterface {

	/**
	 * ObjectManager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Return a MailMessage object prefilled with subject and body from template
	 *
	 * @param string $template Template file
	 * @param array $context Context to use in template
	 * @return \TYPO3\CMS\Core\Mail\MailMessage
	 */
	public function createFluidMail($template, array $context) {
		$message = $this->objectManager->create('TYPO3\CMS\Core\Mail\MailMessage');
		$view = $this->objectManager->create('CmsWorks\CwFluidmail\View\MailView');

		$view->setTemplatePathAndFilename(Util::getFileAbsFileName($template));
		$view->assignMultiple($context);

		list($subject, $plain, $html) = $view->render();
		$message->setSubject(trim($subject));
		if(trim($html)) {
			$message->addPart(trim($html), 'text/html');
		}
		if(trim($plain)) {
			$message->addPart(trim($plain), 'text/plain');
		}

		return $message;
	}
}
?>
