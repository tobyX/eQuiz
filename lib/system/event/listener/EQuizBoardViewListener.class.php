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
class EQuizBoardViewListener implements EventListener
{
	protected static $quiz;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName)
	{
		if ($eventObj->board->eQuizBoard == 0)
			return;

		switch ($eventName)
		{
				case 'readData':
					$eventObj->threadList->sqlSelects .= 'eQuiz.eQuizSeverity, eQuizVoted.userID AS hasVoted, ';
					$eventObj->threadList->sqlJoins .= "	LEFT JOIN 	wcf".WCF_N."_poll eQuiz
													ON 	(eQuiz.messageID = thread.firstPostID
														AND eQuiz.messageType = 'eQuiz')
													LEFT JOIN 	wcf".WCF_N."_poll_vote eQuizVoted
													ON	(eQuizVoted.pollID = eQuiz.pollID 
														AND eQuizVoted.userID = ".WCF::getUser()->userID.")";
				break;

				case 'assignVariables':
					$eventObj->board->enableMarkingAsDone = 1;
					foreach ($eventObj->threadList->threads as $id => $thread)
					{
						if ($thread->eQuizSeverity !== NULL && ($thread->eQuizSeverity > 0 || $thread->eQuizSeverity < 6) && $thread->polls > 0)
						{
							$eventObj->threadList->threads[$id]->prefix .= ' ['.WCF :: getLanguage()->get('wcf.equiz.severity.'.$thread->eQuizSeverity).']';
							$eventObj->threadList->threads[$id]->firstPostPreview = '';
							$eventObj->threadList->threads[$id]->isDone = ($eventObj->threadList->threads[$id]->userID != WCF::getUser()->userID ? (bool)$thread->hasVoted : 1);
						}
					}

					//for WBB Lite
					if (isset($eventObj->threadList->topThreads))
					{
						foreach ($eventObj->threadList->topThreads as $id => $thread)
						{
							if ($thread->eQuizSeverity !== NULL && ($thread->eQuizSeverity > 0 || $thread->eQuizSeverity < 6) && $thread->polls > 0)
							{
								$eventObj->threadList->topThreads[$id]->prefix .= ' ['.WCF :: getLanguage()->get('wcf.equiz.severity.'.$thread->eQuizSeverity).']';
								$eventObj->threadList->topThreads[$id]->firstPostPreview = '';
								$eventObj->threadList->topThreads[$id]->isDone = ($eventObj->threadList->threads[$id]->userID != WCF::getUser()->userID ? (bool)$thread->hasVoted : 1);
							}
						}
					}
				break;
		}
	}
}
?>
