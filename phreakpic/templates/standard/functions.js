function keyon() { keyactive = true; }
function keyoff() { keyactive = false; }

function changeVal(name,val,all)
	{
		i=0;
		while (document.forms['edit_content'].elements[name][i] != undefined)
		{
			if ((selected[i]) || (all))
			{
				document.forms['edit_content'].elements[name][i].value=val;
			}
			i++;
		}
		
	}
	
	
	function changeRadio(name, all)
	{
		i=0;
		while (document.forms['edit_content'].elements[name][i] != undefined)
		{
			if ((selected[i]) || (all))
			{
				
				document.forms['edit_content'].elements[name][i].checked=true;
			}
			i++;
		}
	
	}

	
	function changeCheckbox(name,val,all)
	{
		i=0;
		
		while (document.forms['edit_content'].elements[name][i] != undefined)
		{
			if ((selected[i]) || (all))
			{
				document.forms['edit_content'].elements[name][i].checked=val;
			}
			i++;
		}
		
	}


	function setCheckboxes(the_form, do_check, boxes)
	{
			var elts      = document.forms[the_form].elements[boxes];
			var elts_cnt  = elts.length;
			for (var i = 0; i < elts_cnt; i++) 
			{
					elts[i].checked = do_check;
			} // end for

			return true;
	}
	
		function follow_link()
	{
		location.href = document.getElementsByName('link')[(py*table_cols)+px].href;
	}


	function mark()
	{
		switchTd(px,py);
		setCursor(px,py);
	}

	function switchTd(x,y)
	{
		if (was_shift)
		{
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					if (selected[y*table_cols+x])
					{
						document.getElementsByName('td_thumb')[(y*table_cols)+x].bgColor=backGroundColor;
						selected[y*table_cols+x]=false;
					}
					else
					{
						selected[y*table_cols+x]=true;
						document.getElementsByName('td_thumb')[(y*table_cols)+x].bgColor=selectedColor;
					}	
				}
			}
			
		}
		else
		{
			if (selected[y*table_cols+x])
			{
				document.getElementsByName('td_thumb')[(y*table_cols)+x].bgColor=backGroundColor;
				selected[y*table_cols+x]=false;
			}
			else
			{
				selected[y*table_cols+x]=true;
				document.getElementsByName('td_thumb')[(y*table_cols)+x].bgColor=selectedColor;
			}
		}
	}


	function setCursor(x,y)
	{
		if (document.getElementsByName('td_thumb')[(y*table_cols)+x] == undefined)
		{
			return;
		}
		
		if (selected[y*table_cols+x])
		{
			document.getElementsByName('td_thumb')[(y*table_cols)+x].bgColor=cursorOnSelectedColor;
		}
		else
		{
			document.getElementsByName('td_thumb')[(y*table_cols)+x].bgColor=cursorColor;;
		}
	}
	
	function unsetCursor(x,y)
	{
		i=0;
		if (was_shift)
		{
			while (document.getElementsByName('td_thumb')[i] != undefined)
			{
				if (selected[i])
				{
					document.getElementsByName('td_thumb')[i].bgColor=selectedColor;
				}
				else
				{
					document.getElementsByName('td_thumb')[i].bgColor=backGroundColor;
				}	
				i++;
			}
		}
		else
		{
			if (selected[y*table_cols+x])
			{
				document.getElementsByName('td_thumb')[y*table_cols+x].bgColor=selectedColor;
			}
			else
			{
				document.getElementsByName('td_thumb')[y*table_cols+x].bgColor=backGroundColor;
			}
		}
		
	}


function winheight() {
  return document.all ? document.body.clientHeight : window.innerHeight;
}

function scrollpos() {
  return document.all ? document.body.scrollTop : window.pageYOffset;
}

function animateScrollToDest() {
  scrollPosition = scrollpos();
  dist = Math.abs(scrollPosition - dest)
  if ((dist <= 1) || (speed <= 1) || (dist < speed)) {
    // turns out that the screwy algorithm below doesn't
    // scroll quite to dest. but it's close enough for now.
    return;
  } else if (scrollPosition < dest) {
    delta = speed;
  } else {
    delta = -speed;
  }
  if (dist < 300) {
    speed = speedDefault - (speedDefault * (300 - dist) / 300.0);
  }
  window.scrollBy(0, delta);
  if (scrollpos() - scrollPosition == 0) {
    // haven't necessarily reached destination, but can't scroll anymore
    // ie, reached top or bottom of page
    return;
  }
  setTimeout("animateScrollToDest()", 10);
}




function scrollToMidpage(name, index , margin, animate) {
  windowHeight = winheight();
  scrollPosition = scrollpos();
  objectPosition = document.getElementsByName(name)[index].offsetTop+document.getElementsByName(name)[index].offsetHeight;
  if ((objectPosition > scrollPosition + windowHeight - margin) || (objectPosition < scrollPosition)) 
	{
		
    dest = objectPosition - (windowHeight * midfraction);
    if (animate) {
      speed = speedDefault;
      animateScrollToDest();
    } else {
      window.scrollTo(0, dest);
    }
  }
}

function up(shift)
{
	if (py>0)
	{
		if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			py=py-1;
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{		
			unsetCursor(px,py)
			was_shift=false;
			py=py-1;
			setCursor(px,py);
		}
		
		
	}
	scrollToMidpage('td_thumb', (py*table_cols)+px , 500, animate);
}

function down(shift)
{
	if (document.getElementsByName('td_thumb')[((py+1)*table_cols)+px] != undefined)
	{
		if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			py=py+1;
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{
			unsetCursor(px,py)
			was_shift=false;
			py=py+1;
			setCursor(px,py);
		}
		
	}
	scrollToMidpage('td_thumb', (py*table_cols)+px , 500, animate);

}

function left(shift)
{
	if (px>0)
	{
	if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			px=px-1;
			
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{
			unsetCursor(px,py)
			was_shift=false;
			px=px-1;
			setCursor(px,py);
		}
	}
}

function right(shift)
{
	if (((px+1) < table_cols) && (document.getElementsByName('td_thumb')[(py*table_cols)+px+1] != undefined))	
	{
		if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			px=px+1;
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{
			unsetCursor(px,py)
			was_shift=false;
			px=px+1;
			setCursor(px,py);
		}
	}
}


function getkey_cat(e) 
{
	if (!keyactive) return true;
	if (e == null) 
	{ 
		kcode = event.keyCode;
	} else 
	{ // mozilla
		if (e.altKey || e.ctrlKey) 
		{
			// moz doesn't override ctrl keys,
			// eg, Ctrl-N won't bypass this function to open new window
			return true;
		}
		kcode = e.which;
	}
	key = String.fromCharCode(kcode);
	// allow some keys to work w/o triggering prekey

//	if (allowkeys.indexOf(key) == -1) {
//		if (xprekey()) return true;
//	}
	switch(key) 
	{
		case "w": up(false); return false;
		case "s": down(false); return false;
		case "a": left(false); return false;
		case "d": right(false); return false;
		
		case "W": up(true); return false;
		case "S": down(true); return false;
		case "A": left(true); return false;
		case "D": right(true); return false;
		case " ": mark(); return false;
	}
	if (kcode==13)
	{
		follow_link();
	}
	return true;
}

function getkey_content(e) 
{
	if (!keyactive) return true;
	if (e == null) 
	{ 
		kcode = event.keyCode;
	} else 
	{ // mozilla
		if (e.altKey || e.ctrlKey) 
		{
			// moz doesn't override ctrl keys,
			// eg, Ctrl-N won't bypass this function to open new window
			return true;
		}
		kcode = e.which;
	}
	key = String.fromCharCode(kcode);
	// allow some keys to work w/o triggering prekey

//	if (allowkeys.indexOf(key) == -1) {
//		if (xprekey()) return true;
//	}
	switch(key) 
	{
		case "a": view_next(); return false;
		case "d": view_prev(); return false;		
	}
	if (kcode==13)
	{
		to_thumbs();
	}
	return true;
}



function to_thumbs()
{
	location.href = document.getElementsByName('thumbs_link')[0].href;
}

function view_next()
{
	location.href = document.getElementsByName('next_link')[0].href;
}

function view_prev()
{
	location.href = document.getElementsByName('prev_link')[0].href;
}