/*var d = new Date;
	var month = d.getMonth();
	var year = d.getFullYear();
	var total = 0;
 	for(var i = 0; i < month_name.length; i++) {
 		var day_month = $("<div>").addClass("day-month");
 		day_month.appendTo(".day-year");
		var daysOfMonth = new Date(year, i+1, 0).getDate();
		for(var j = 1; j <= daysOfMonth; j++) {
 			var day = $('<div>').addClass('day');
 			day.appendTo(day_month);
 			day.text(j);
		}
		var width = $('.day').outerWidth();
		total += width*daysOfMonth;
		day_month.css('width', width*daysOfMonth);
		$(".day-year").css("width", total);
 		var month_year = $("<div>").addClass("month-year");
		month_year.css('width', width*daysOfMonth);
		month_year.appendTo(".month");
		$(".month").css("width", total);
		var p = $('<p>');
		p.text(month_name[i] + ' ' + year);
		p.appendTo(month_year)
 	}*/