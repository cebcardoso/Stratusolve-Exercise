$(document).ready(function(){
    var currentTaskId = -1;
	var action = '';
	$InputTaskDescription = $('#InputTaskDescription');
	$InputTaskName = $('#InputTaskName');
	$alertElement = $('.alert');
	
    $('#myModal').on('show.bs.modal', function (event) {
		$alertElement.addClass('hidden');
        var triggerElement = $(event.relatedTarget); // Element that triggered the modal
        var modal = $(this);
		
        if (triggerElement.attr("id") == 'newTask') {
            modal.find('.modal-title').text('New Task');
			$InputTaskName.val("");
			$InputTaskDescription.val("");
            $('#deleteTask').hide();
			$('#saveTask').hide();
            currentTaskId = -1;
        } else {
            modal.find('.modal-title').text('Task details');
            $('#deleteTask').show();
            currentTaskId = triggerElement.attr("id");
			$InputTaskName.val(triggerElement.find('.list-group-item-heading').html());
			$InputTaskDescription.val(triggerElement.find('.list-group-item-text').html());
        }
    });
	
    $('#saveTask').click(function() {
		action = 'save';
		performTaskAction();
        $('#myModal').modal('hide');
        updateTaskList();
    });
	
    $('#deleteTask').click(function() {
        action = 'delete';
		
		performTaskAction();
		$('#myModal').modal('hide');
		updateTaskList();
    });
	
	$InputTaskName.keyup(function() {
		if ($(this).val() != "" && $InputTaskDescription.val() != "") {
			$('#saveTask').show();
		} else {
			$('#saveTask').hide();
		}
	});
	
	$InputTaskDescription.keyup(function() {
		if ($(this).val() != "" && $InputTaskName.val() != "") {
			$('#saveTask').show();
		} else {
			$('#saveTask').hide();
		}
	});
	
    function updateTaskList() {
        $.post("list_tasks.php", function(data) {
            $("#TaskList").html(data);
        });
    }
	
	function performTaskAction() {
		$.post("update_task.php", {action: action, taskId: currentTaskId, taskName: $InputTaskName.val(), taskDescription: $InputTaskDescription.val()})
			.done(function(dataReturned) {
				updateTaskList();
				$alertClass = 'alert-success';
				$alertMessageStrong = 'Success!';
				$alertMessageText = action + ' complete';
				
				if (dataReturned == 0) {
					$alertClass = 'alert-danger';
					$alertMessageStrong = 'Error!';
					$alertMessageText = action + ' error';
				}
				
				$alertElement.addClass($alertClass);
				$alertElement.removeClass('hidden');
				$alertElement.find('strong').html($alertMessageStrong);
				$alertElement.find('span').html($alertMessageText);
		});
	}
	
    updateTaskList();
});