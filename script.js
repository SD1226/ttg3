$(document).ready(function(){
	var bg = ["url(pin.png)", "url(unpin.png)"];
	
	
	
	function activate()
	{
        $("#add").click(function(){ 
		    add(); 
		    });
		$(".edit").click(function(e){ 
		    e.stopPropagation();
		    edit($(this).parent()); 
			});
		$(".delete").click(function(e){
			e.stopPropagation();			
			del($(this).parent()); 
			});	
		$(".pin").click(function(e){
			e.stopPropagation();			
		    pinup($(this).parent()); 
	        });	
	}
	
	
	
	function deactivate()
	{
		$("#add").unbind("click");
		$(".edit").unbind("click");
		$(".delete").unbind("click");
		$(".pin").unbind("click");
	}
	
	
	
	function del(item)
	{
		var index = $('.note').index(item);	
		$.post("delete.php",{note_id: ids[index]},
		function(data, status)
		{
			if((status == 'success')&&data)
			{
				item.remove();
				ids.splice(index,1);
				pins.splice(index,1);
			}
			else
			{
				alert("Error: Failed to save changes!");
			}
		});
	}
	
	
	
	function pinup(item)
	{
		var index = $('.note').index(item);	
		if(pins[index] == 0)
		{
			pins[index] = 1;
		}
		else
		{
			pins[index] = 0;
		}
		$.post("pinup.php",{note_id: ids[index], pin: pins[index]},
		function(data, status)
		{
			if((status == 'success')&&data)
			{
				item.find('.pin').css("background-image", bg[pins[index]]);
				item.css("order", pins[index]);
			}
			else
			{
				alert("Error: Failed to save changes!");
			}
		});
	}
	
	
	
	function save(item)
	{
		var text = item.find('.fcontent').val();
		var title = item.find('.ftitle').val();
		var now = new Date();
		var edate = now.toISOString().slice(0,10);
		$.post("save.php",{title: title, text: text, edate: edate},
		function(data, status)
		{
			if((status == 'success')&&data)
			{
				data = JSON.parse(data);
				item.html(data.note);
				ids.push(data.id);
				pins.push(1);
			}
			else
			{
				alert("Error: Failed to save changes!");
			}
		});
	}
	
	
	
	function modify(item)
	{
		var text = item.find('.fcontent').val();
		var title = item.find('.ftitle').val();
		var now = new Date();
		var edate = now.toISOString().slice(0,10);
		var index = $('.note').index(item);	
		$.post("modify.php",{title: title, text: text, edate: edate, note_id: ids[index]},
		function(data, status)
		{
			if((status == 'success')&&data)
			{
				data = JSON.parse(data);
				item.html(data.note);
			}
			else
			{
				alert("Error: Failed to save changes!");
			}
		});
	}

	
	
	function add()
	{
	    deactivate();
		var prev = $("#content").html();
		$("#content").html(prev+'<div class="note"><form>'+
		'<input type="text" class="ftitle" placeholder="Note title ..." maxlength="50" required />'+
        '<textarea class="fcontent" placeholder="Type your note here ..." maxlength="500" required ></textarea>'+
        '<button type="submit" class="save">SAVE</button>'+
        '<button type="button" class="cancel">CANCEL</button></form></div>');
		var item = $(".note:last");
		item.css("order", 1);
		item.find(".cancel").click(function(e){ 
		    e.stopPropagation();
		    item.remove();
            activate();
	        });			
		item.find("form").submit(function(e){ 
            e.stopPropagation();
		    save(item);
            activate();
			});		
	}	
	
	
	
	function edit(item)
	{
        deactivate();
		var old = item.html();
		var title = item.find('.ntitle').text();
		var text = item.find('.ncontent').text();
		item.html('<form><input type="text" class="ftitle" placeholder="Note title ..." maxlength="50" required />'+
        '<textarea class="fcontent" placeholder="Type your note here ..." maxlength="500" required ></textarea>'+
        '<button type="submit" class="save">SAVE</button>'+
        '<button type="button" class="cancel">CANCEL</button></form>');
		item.find('.ftitle').val(title);
		item.find('.fcontent').val(text);
		item.find('.cancel').click(function(e){ 
		    e.stopPropagation();
		    item.html(old);
			activate();
			});
		item.find("form").submit(function(e){ 
		    e.stopPropagation();
		    modify(item);
			activate();
			});	
	}

	
	
	$("#dd").click(function(){ $("#dBar").toggle(); });
	$('.pin').each(function(index)
	{
		if(pins[index])
		{
			$(this).css("background-image", "url(unpin.png)");
			$(this).parent().css("order", 1);
		}
		else
		{
			$(this).css("background-image", "url(pin.png)");
			$(this).parent().css("order", 0);			
		}
	});
	activate();

});