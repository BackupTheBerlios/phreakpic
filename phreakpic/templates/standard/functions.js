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
		if (py>=0)
		{	
			location.href = document.getElementsByName('link')[(py*table_cols)+px].href;
		}
		else
		{
			location.href = document.getElementsByName('cat_link')[cat_amount+py].href;
		}
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
		if ((x<0) || (y<0) || (document.getElementsByName('td_thumb')[y*table_cols+x] == undefined))
		{
			return;
		}
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

function moveCursor(dx,dy,shift)
{
	if (cat_amount>0)
	{
		if ( ((py+dy)<0) )
		{
			if (py==0)
			{
				unsetCursor(px,py);
			}
			if ((py+dy+cat_amount) >= 0)
			{

				if (cat_amount+py<cat_amount)
				{
					document.getElementsByName('td_cat')[cat_amount+py].bgColor=backGroundColor;
				}
				py+=dy;
				if ((document.getElementsByName('td_cat')[cat_amount+py] != undefined))
				{
					document.getElementsByName('td_cat')[cat_amount+py].bgColor=cursorColor;
				}
			}
			return;
		}

		if (((py+dy)==0) && (document.getElementsByName('td_thumb')[0] != undefined))
		{
			document.getElementsByName('td_cat')[cat_amount-1].bgColor=backGroundColor;
		}
	}

	if ((px+dx)<0)
	{
		if (py<1)
		{
			return;
		}
		unsetCursor(px,py)
		dx=0;
		px=table_cols-1;
		dy=-1;
		
	}
	if ((px+dx)>=table_cols)
	{
		if ((document.getElementsByName('td_thumb')[(py+1)*table_cols] == undefined))
		{
			return;
		}
		unsetCursor(px,py)
		dx=0;
		px=0;
		dy=1;
	}
	
	if ((document.getElementsByName('td_thumb')[((py+dy)*table_cols)+px+dx] != undefined))
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
			px=px+dx;
			py=py+dy;
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
			px=px+dx;
			py=py+dy;
			setCursor(px,py);
		}
		
		
	}
	scrollToMidpage('td_thumb', (py*table_cols)+px , 500, animate);
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
		case "p": 
		case "b":
			catup(); return false; 
		case "w": moveCursor(0,-1,false); return false;
		case "s": moveCursor(0,1,false); return false;
		case "a": moveCursor(-1,0,false); return false;
		case "d": moveCursor(1,0,false); return false;
		case "1": 
		case "2": 
		case "3":
		case "4":
		case "5":
		case "6":
		case "7":
		case "9":
			if (document.getElementsByName('nav_link')[key-1] != undefined)
			{
				location.href = document.getElementsByName('nav_link')[key-1].href;; return false;
			}
		
		case "W": moveCursor(0,-1,true); return false;
		case "S": moveCursor(0,1,true); return false;
		case "A": moveCursor(-1,0,true); return false;
		case "D": moveCursor(1,0,true); return false;
		case " ": mark(); return false;
		case "f": follow_link(); return false;
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
		case "f": to_thumbs(); return false;	
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

function tog(id) {
  //e = document.getElementById(id);
  //e[1].style.display = (e.style.display == "none") ? "inline" : "none";
	i=0;
	while(document.getElementsByName(id)[i] != undefined)
	{
		document.getElementsByName(id)[i].style.display =  (document.getElementsByName(id)[i].style.display == "none") ? "inline" : "none";
		i++;
	}
}

function catup()
{
	location.href = document.getElementsByName('nav_link')[catback].href;
}
