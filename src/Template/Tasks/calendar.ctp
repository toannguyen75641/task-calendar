
<div id="wrapper">
	<div id="task_list" class="taskList">
	</div>
	<div id="calendar">
		<div id="header">
			<div id="month-year">
			</div>
			<div id="days_range" class="day-year">
			</div>
		</div>
		<div id="task_calendar" class="task-calendar">
			<div id="background_lines">
			</div>
			<div id="task_wrapper">
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	* {
		margin: 0px;
		padding: 0px;
	}
	#wrapper {
		margin-top: 10px;
		height: 500px;
	}
	.taskList {
		float: left;
		height: 100%;
		width: 166px;
		border: solid 1px #007bff;
		border-radius: 5px;
	}
	#calendar {
		float: right;
		border: solid 1px #007bff;
		height: 100%;
		width: 85%;
		overflow: auto;
		border-radius: 5px;
	}
	#header {
		display: block;
		height: 15%;
	}
	#month-year {
		height: 60%;
	}
	.month {
		border: solid 1px #007bff;
	}

	.day-year {
		height: 42%;
	}

	.day {
		display: inline-block;
		border: 1px solid #007bff;
		text-align: center;
		width: 30px;
		height: 30px;
	}
	.day.saturday { 
		color: orange; 
	}
	.day.sunday { 
		color: red; 
	}

	.task-calendar {
		position: relative;
		height: 100%;
	}
	#background_lines {
		height: 100%;
		position: absolute;
	}
	.col_task {
		height: 100%;
		width: 30px;
		display: inline-block;
		border: 1px solid #007bff;
		opacity: 0.2;
	}
	.task-wrapper {
		position: relative;
		top: 5px;
		left: 0;
		height: 30px;
	}
	.task {
		position: absolute;
		border-radius: 5px;
		background-color: #007bff;
		height: 25px;
		box-shadow: 2px 2px 4px rgba(0,0,0,0.5);
	}
	.task::after {
		content: " ";
	    background-color: #ccc;
	    position: absolute;
	    box-shadow: 2px 2px 4px rgba(0,0,0,0.5);
	    right: 0px;
	    width: 4px;
	    height: 100%;
	    cursor: w-resize;
		}
	.task-name {
		position: absolute;
		left: 10px;
		z-index: 9999;
	}

	.progress {
		position: absolute;
		height: 25px;
		background-color: #20c997;
		border-radius: 5px;
		border: none;
	}
	.right-task {
		position: absolute;
	}

</style>

<script type="text/javascript">
	var monthName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	
	var taskList = JSON.parse('<?= json_encode($tasks) ?>');
	var startDate = false;
	var endDate = false;
	const oneDay = 60*60*24*1000;

	var daysRange = $("#days_range");
	var taskWrapper = $("#task_wrapper");
	var taskCalendar = $("#task_calendar");

	//startDate, endDate
	for (var i = 0; i < taskList.length; i++) {
		_task = taskList[i];
		_task_start = new Date(_task.start_date);
		_task_end = new Date(_task.end_date);

		if (!startDate || (_task_start < startDate)) {
			startDate = _task_start;
		}

		if (!endDate || (_task_end > endDate)) {
			endDate = _task_end;
		}
	}
	
	//startDate -> endDate
	function calculatePeriod(_start, _end) {
		var startPeriod =  new Date(_start).getTime();
		var endPeriod =  new Date(_end).getTime();
		var period = (endPeriod - startPeriod) / oneDay;
		return Math.round(period + 1);
	}

	var totalDays = calculatePeriod(startDate, endDate);
	taskCalendar.css('width', totalDays*30);   

	//render task
	function renderTask(_task) {
		var rowTask = $("<div>").addClass("task-wrapper");
		rowTask.appendTo(taskCalendar);
		var task = $("<div>").addClass("task");
		var _offsetLeft = calculatePeriod(startDate, _task.start_date);
		var _taskWidth = calculatePeriod(_task.start_date, _task.end_date);
		task.css('left', (_offsetLeft-1)*30);
		task.css('width', (_taskWidth)*30);
		rowTask.append(task);
		var progress = $("<div>").addClass("progress");
		progress.css('width', _task.progress+'%');
		progress.appendTo(task);
		var taskName = $("<div>").addClass("task-name").text(_task.name);
		taskName.appendTo(task);
		var rightTask = $("<div>").addClass("right-task").text(_task.progress+'%');
		rightTask.css('left', (_taskWidth)*30);
		rightTask.appendTo(task);
		// var leftTask = $("div");
		// <i class="fas fa-user"></i>
		return rowTask;
	}

	for (var i = 0; i < taskList.length; i++) {
		var _taskElement = renderTask(taskList[i]);
		taskWrapper.append(_taskElement);
	}

	//render day
	for (var d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
		var _today = d.getDay();
		var _newDay = $("<div>").addClass("day").text(d.getDate());
		var _colTask = $("<div>").addClass('col_task');
		_colTask.appendTo(background_lines);
		if (_today == 6) {
			_newDay.addClass('saturday');
		}

		if (_today == 0) {
			_newDay.addClass('sunday');
		}

	    daysRange.append(_newDay);
	}

	daysRange.css('width', totalDays*30);

	//resize task
	var tasks = document.getElementsByClassName('task');
	var movePosition;

	function addEventListenerList(list, event, fn) {
		for(var i = 0; i < list.length; i++) {
			list[i].addEventListener(event, fn);
		} 
	}

	function installResize (e) {
		movePosition = e.clientX;
		var _this = this;
		document.addEventListener('mousemove', function resize(e) {
			var dx = e.clientX - movePosition;
			if(dx % 30 == 0 || dx % -30 == 0) {
				movePosition = e.clientX;
				_this.style.width = (parseInt(getComputedStyle(_this, null).width) + dx) + "px";
			}
			document.addEventListener("mouseup", function() {
	    		document.removeEventListener("mousemove", resize);
	    		
	    		$.ajax({
	    			type: "post",
	    		});
			});
		});
		
	} 

	addEventListenerList(tasks, 'mousedown', installResize);

</script>