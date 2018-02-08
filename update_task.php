<?php
/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('Task.class.php');
// Assignment: Implement this script
$html = 'Error';

if ($_POST) {
	$task = new Task($_POST['taskId']);
	$task->TaskName = $_POST['taskName'];
	$task->TaskDescription = $_POST['taskDescription'];
	
	if (null != $_POST['action'] && $_POST['action'] == 'delete') {
		$html = $task->Delete();
	} else {
		$html = $task->Save();
	}
}

echo $html;
?>