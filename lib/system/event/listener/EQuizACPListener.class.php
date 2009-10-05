<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @package		com.toby.wbb.equiz
 * @author		Tobias Friebel
 * @copyright	2008 Tobias Friebel
 * @license		CC Namensnennung-Keine kommerzielle Nutzung-Keine Bearbeitung <http://creativecommons.org/licenses/by-nc-nd/2.0/de/>
 */
class EQuizACPListener implements EventListener
{
	private $eQuizBoard = 0;
	private $isSave = false;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName)
	{
		switch ($eventName)
		{
			case 'readFormParameters':
				if (isset($_POST['eQuizBoard']))
					$this->eQuizBoard = 1;
				else
					$this->eQuizBoard = 0;
			break;

			case 'save':
				$eventObj->additionalFields['eQuizBoard'] = $this->eQuizBoard;
				$this->isSave = true;
			break;

			case 'assignVariables':
				if (is_object($eventObj->board) && !$this->isSave)
				{
					WCF::getTPL()->assign(array(
						'eQuizBoard' => $eventObj->board->eQuizBoard,
					));
				}
				else
				{
					WCF::getTPL()->assign(array(
						'eQuizBoard' => $this->eQuizBoard,
					));
				}

				WCF::getTPL()->append('additionalSettings', WCF::getTPL()->fetch('eQuizACP'));
			break;
		}
	}
}
?>