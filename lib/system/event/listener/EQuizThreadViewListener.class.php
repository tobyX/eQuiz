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
class EQuizThreadViewListener implements EventListener
{
	protected static $quiz;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName)
	{
		if ($eventObj->board->eQuizBoard == 0)
			return;

		if (!self :: $quiz)
		{
			$sql = "SELECT userID, pollID
					FROM wbb" . WBB_N . "_post
					WHERE postID = ".$eventObj->thread->firstPostID;

			$data = WCF :: getDB()->getFirstRow($sql);

			if ($data['pollID'] != 0)
				self :: $quiz = new EQuizHandler($data['pollID'], $eventObj->board->getPermission('canVotePoll'), 'index.php?page=Thread', 'eQuiz', $data['userID']);
		}

		if (self :: $quiz === NULL)
			return;

		switch ($eventName)
		{
			case 'readData':
				if (!self :: $quiz->getQuiz()->showResult())
				{
					$eventObj->pageNo = 1;
					$eventObj->itemsPerPage = 1;
				}
			break;

			case 'assignVariables':
				WCF :: getTPL()->assign('quizID', $eventObj->postList->posts[0]->pollID);
				WCF :: getTPL()->assign('eQuizPostID', $eventObj->postList->posts[0]->postID);
				WCF :: getTPL()->assign('eQuiz', self :: $quiz->getQuiz());

				$eventObj->postList->posts[0]->pollID = 0;

				if (!self :: $quiz->getQuiz()->showResult())
				{
					$eventObj->pages = 1;
				}
			break;
		}
	}
}
?>