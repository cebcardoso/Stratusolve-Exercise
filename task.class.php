<?php
/**
 * This class handles the modification of a task object
 */
class Task {
	public $fileName = 'Task_Data.txt';
    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataSource;
    public function __construct($Id = null) {
        $this->TaskDataSource = file_get_contents($this->fileName);
        if (strlen($this->TaskDataSource) > 0)
            $this->TaskDataSource = json_decode($this->TaskDataSource); // Should decode to an array of Task objects
        else
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array

        if (!$this->TaskDataSource)
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        if (!$this->LoadFromId($Id))
            $this->Create();
    }
    protected function Create() {
        $this->TaskId = $this->getUniqueId();
        $this->TaskName = 'New Task';
        $this->TaskDescription = 'New Description';
    }
    protected function getUniqueId() {
		$id = 1;
		$maxId = $id;
		
		if ($this->TaskDataSource && !empty($this->TaskDataSource)) {	
			foreach ($this->TaskDataSource as $index => $task) {
				if ($task->TaskId >= $maxId) {
					$maxId = $task->TaskId;
				}
			}
			
			$id = $maxId + 1;
		}
        
        return $id;
    }
    protected function LoadFromId($Id = null) {
		$exists = false;
		
		if (null != $Id && !empty($this->TaskDataSource)) {
			foreach ($this->TaskDataSource as $index => $task) {
				if ($task->TaskId == $Id) {
					$this->TaskId = $Id;
					$this->TaskName = $task->TaskName;
					$this->TaskDescription = $task->TaskDescription;
					$exists = true;
					break;
				}
			}
        }
		
		return $exists;
    }
	
	protected function WriteToFile() {
		$return = false;
		$this->noZeroElementFix();
		
		if (file_exists($this->fileName)) {
			$myfile = fopen($this->fileName, 'w');
			fwrite($myfile, json_encode($this->TaskDataSource));
			fclose($myfile);
			$return = true;
		}
		
		return $return;
	}
		
	protected function noZeroElementFix() {
		$newArray = array();
		
		foreach ($this->TaskDataSource as $task) {
			array_push($newArray, $task);
		}
		
		$this->TaskDataSource = $newArray;
	}

    public function Save() {
		$exists = false;
		
		foreach ($this->TaskDataSource as $index => $task) {
			if ($task->TaskId == $this->TaskId) {
				$this->TaskDataSource[$index]->TaskName = $this->TaskName;
				$this->TaskDataSource[$index]->TaskDescription = $this->TaskDescription;
				$exists = true;
				break;
			}
		}
		
		if (!$exists) {
			array_push($this->TaskDataSource, array('TaskId' => $this->TaskId, 'TaskName' => $this->TaskName, 'TaskDescription' => $this->TaskDescription));
		}
		
		return $this->WriteToFile();
    }
	
    public function Delete() {
		foreach ($this->TaskDataSource as $index => $task) {
			if ($task->TaskId == $this->TaskId) {
				unset($this->TaskDataSource[$index]);
			}
		}

		return $this->WriteToFile();
    }
	
	public function pr($array) {
		echo '<pr>';
		print_r($array);
		echo '</pr>';
	}
}
?>