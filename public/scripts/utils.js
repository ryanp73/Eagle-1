function getValue(array, index, column) 
{
	if (isNaN($(array[index].children[column]).text()))
	{
		return $(array[index].children[column]).text();
	}
	return parseInt($(array[index].children[column]).text());
}

function sort(e, column, isReversed) 
{
	table = $(e.target).closest('table');
	console.log(e);
	if (!isReversed)
	{
		var trs = $(table).find('tbody>tr').get();
		for (var i = 0; i < trs.length - 1; i++)
		{
			for (var j = i + 1; j < trs.length; j++)
			{
				var maxValue = getValue(trs, i, column);
				var maxIndex = i;
				if (getValue(trs, j, column) > maxValue)
				{
					temp = trs[j];
					trs[j] = trs[maxIndex];
					trs[maxIndex] = temp;
					maxIndex = j;
					maxValue = getValue(trs, maxIndex, column);
				}
			}
		}
	}
	else
	{
		var trs = $(table).find('tbody>tr').get();
		for (var i = 0; i < trs.length - 1; i++)
		{
			for (var j = i + 1; j < trs.length; j++)
			{
				var minValue = getValue(trs, i, column);
				var minIndex = i;
				if (getValue(trs, j, column) < minValue)
				{
					temp = trs[j];
					trs[j] = trs[minIndex];
					trs[minIndex] = temp;
					minIndex = j;
					minValue = getValue(trs, minIndex, column);
				}
			}
		}
	}
	for (var i = 0; i < trs.length; i++)
	{
		$(table).find('tbody>tr:last').after($(trs[i]));
	}
}

function getStartDate(array, i) 
{
	return $(array[i]).find('span').text().split(' - ')[0] + ', 2016';
}

function convertStartDate(array, i) 
{
	return new Date(getStartDate(array, i)).getTime() / 1000;
}

function sortEvents() 
{
	var events = $('li.event').get();
	for (var i = 0; i < events.length - 1; i++) 
	{
		for (var j = i; j < events.length; j++) 
		{
			if (convertStartDate(events, i) > convertStartDate(events, j))
			{
				var temp = events[i];
				events[i] = events[j];
				events[j] = temp;
			}
		}
	}
	for (var i = 0; i < events.length; i++)
	{
		$('ul#events>li:last').after($(events[i]));
	}
}

function defenseRatingText(table)
{
	$.each($(table + '>td').toArray(), function(i, el) {
		text = $(el).text();
		if (parseInt(text) != NaN)
		{ 
			if ($(el).prev().text().indexOf('Number') == -1)
			{
				console.log(parseInt(text));
				if (parseInt(text) == 0) 
				{
					$(el).text("Cannot do.");
				}
				else if (parseInt(text) == 1)
				{
					$(el).text("Inconsistent.");
				}
				else if (parseInt(text) == 2)
				{
					$(el).text("Consistent.");
				}
			}
		} 
	});
}