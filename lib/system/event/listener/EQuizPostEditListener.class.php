<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/equiz/EQuizEditor.class.php');
require_once(WCF_DIR.'lib/data/message/equiz/EQuizHandler.class.php');

/**
 * @package		com.toby.wbb.equiz
 * @author		Tobias Friebel
 * @copyright	2008 Tobias Friebel
 * @license		CC Namensnennung-Keine kommerzielle Nutzung-Keine Bearbeitung <http://creativecommons.org/licenses/by-nc-nd/2.0/de/>
 */
class EQuizPostEditListener implements EventListener
{
	protected static $quiz;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName)
	{
		if ($eventObj->board->eQuizBoard == 0 || $eventObj->thread->firstPostID != $eventObj->postID)
			return;

		switch ($eventName)
		{
			case 'assignVariables':
				$eventObj->showPoll = false;
			break;

			case 'show':
				if (!$eventObj->pollEditor instanceof EQuizEditor)
					$eventObj->pollEditor = new EQuizEditor($eventObj->post->pollID);
				WCF :: getTPL()->append('additionalSubTabs', WCF :: getTPL()->fetch('eQuizEdit'));
				WCF :: getTPL()->append('additionalTabs', WCF :: getTPL()->fetch('eQuizTabHeader'));
			break;

			case 'submit':
				$eventObj->pollEditor = new EQuizEditor($eventObj->post->pollID);
			break;
		}
	}
}
?>