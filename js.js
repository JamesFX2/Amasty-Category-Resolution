var loadOut;
var version = 0;


$(window).on('load', function() {
	

	setAllFalse();
	loadSelection();
	$('#container > li > ul > li').on("mousedown", function(event) {
	if(event.which == 3)
	{
	event.preventDefault();
	var ph = $(this).find('ul li');
	ph.toggle();
	}
	else
	{
		var ph = $(this).find('ul li');
		var test = $(this).find('ul li').is(":visible");
		if($(this).data("keep"))
		{
			$(this).data("keep",false);
			$(this).css("background-color","white");
			version++;
			processInput();
		}
		else
		{
			$(this).css("background-color","pink");
			$(this).data("keep",true);
			version++;
			processInput();
		}
		if(test)
		{
			ph.toggle();
		}
	}

	});

	$('#container > li > ul > li').on("contextmenu", function(event) {
	if(event.which == 3)
	{
	event.preventDefault();
	}


	});
	$('h3').click(function() {
		var output  = {};
		$('#container > li > ul > li').each(function() {
			if($(this).data("keep"))
			{
				var item = $(this).find('span').text();
				var ph = $(this).parent().closest('li').find('a');
				var page = ph.text();
				if (typeof output[page] !== 'undefined')
				{
					output[page].push(item);
				}
				else
				{
					output[page] = [item];
					
				}
				
			};
		});
		var text ="";
		for(var x in output)
		{
			text +="\""+x+"\",\""+output[x].join('","')+"\"\n";
		}
		$('textarea').val(text);
		hello.data = output;
	});	

});


function setAllFalse() {
	
	$('#container > li > ul > li').each(function() {
		$(this).data("keep",false);
		$(this).css("background-color","white");
	});
}

function processInput()
{
	var hello = {};
	var output  = {};
	$('#container > li > ul > li').each(function() {
		if($(this).data("keep"))
		{
			var item = $(this).find('span').text();
			var ph = $(this).parent().closest('li').find('a');
			var page = ph.text();
			if (typeof output[page] !== 'undefined')
			{
				output[page].push(item);
			}
			else
			{
				output[page] = [item];
				
			}
			
		};
	});
	hello.data = output;
	hello.version = version;
	
	$.ajax({
	type: "POST",
	url: "process.php",
	data: hello,
	dataType: "json"
	}).done(function(data) {
	 console.log(data);
	});
	
}

function loadSelection() {
	$.ajax({
	dataType: "json",
	url: "process.php?version="+version,
	success: function(data) {
		loadOut = data;
		if (version < data.version) {	
			version = data.version;
			setAllFalse();
			for(var x in loadOut.data)
			{
				var ph = $('li[data-href="'+x+'"]')[0];
				for(var y in loadOut.data[x])
				{
					var ph1 = $(ph).find('ul li span[data-tag="'+loadOut.data[x][y]+'"]').parent();
					ph1.data("keep",true).css("background-color","pink");
					
				}
			}
		}
		
	}
	});
}

setInterval(function() {
	loadSelection();
},30e3);