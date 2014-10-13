<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$website_title}</title>
	<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8" />
	{$stylesheets}
	<script type="text/javascript" src="js/slideinmenu.js"></script>
	<script type="text/javascript"> 
		var menu;
		function loaded() {
			document.addEventListener('touchmove', function(e){ e.preventDefault(); e.stopPropagation(); });
			menu = new slideInMenu('slidedownmenu', false);
		}
		document.addEventListener('DOMContentLoaded', loaded);
	</script>

</head>
<body>
<div style="display:inline;">
	<div id="slidedownmenu">
		<ul>
			<li><img src="templates\admin1\css\icon1.png" width="59" height="60" alt="" />Option 1</li>
			<li><img src="templates\admin1\css\icon2.png" width="59" height="60" alt="" />Option 2</li>
			<li><img src="templates\admin1\css\icon3.png" width="59" height="60" alt="" />Option 3</li>
			<li><img src="templates\admin1\css\icon4.png" width="59" height="60" alt="" />Option 4</li>
		</ul>
		<div class="handle"></div>
	</div>
	<div id="container">
		{$navigation}
		
		{include file="admin1/header.tpl"}
		
		<div id="content">
			{$content}
		</div>
		
		{include file="admin1/footer.tpl"}
	</div>
</div>
</body>
</html>