<html>
<!--{config_load file="test.cfg"}-->
<head>
<title><!--{#global_title#}--><!--{$title|default:""}--></title>
</head>

<body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->">
 
<div align="center">
  <table width="95%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td height="45"><img src=<!--{$thumbnail_last_file}--> width="<!--{$thumbnail_last_width}-->" height="<!--{$thumbnail_last_height}-->"><br>
        Zur&uuml;ck </td>
      <td height="45">fehlt: Sitenavigation</td>
      <td height="45"> 
        <div align="right"><img src=<!--{$thumbnail_next_file}--> width="<!--{$thumbnail_next_width}-->" height="<!--{$thumbnail_next_height}-->"><br>
          Weiter </div>
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2">fehlt: aktueller Ort</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>fehlt: Poll</td>
      <td> 
        <div align="center"><img src=<!--{$file}--> width="<!--{$content_size.width}-->" height="<!--{$content_size.height}-->"></div>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3">
        <div align="center">fehlt: Kommentare</div>
      </td>
    </tr>
  </table>
  
</div>
</body>
</html>