<html>
<!--{config_load file="standard.cfg"}-->
<head>
<title><!--{$title|default:""}--></title>
</head>
<body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->">



<form action="view_<!--{$type}-->.php?cat_id=<!--{$cat_id}--><!--{$oontent_id_string}--><!--{$sid}-->" method="POST">
 

<!--{$lang.topic}--> <input type="text" name="topic" value="<!--{$topic}-->">
<br><!--{$lang.text}--><br>
<textarea name="comment_text" cols="70" rows="10"><!--{$text}--></textarea>

<input type="hidden" name="mode" value="<!--{$mode}-->">
<input type="hidden" name="parent_id" value="<!--{$parent_id}-->">

<input type="submit" value="<!--{$lang.send}-->">   

</form>





</body>
</html>
