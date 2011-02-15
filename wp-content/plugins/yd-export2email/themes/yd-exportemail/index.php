<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="fr">
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
</head>
<body bgcolor="#ffffff" text="#000000">
	<style>
	img { float:left; margin: 0 5px 0 0; width: 120px; height: 120px; border: 0; }
	</style>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td bgcolor="#666666" align="center"><br><br>
				<table border="0" cellpadding="0" cellspacing="0" width="90%">
					<thead>
					<tr height="100">
						<td valign="top" width="15" height="60" bgcolor="#ffffff">&nbsp;</td>
						<td valign="top" width="60" height="60" bgcolor="#ffffff"><a href="http://www.yann.com/en/wp-plugins/yd-export2email"><img src="<?php echo yd_get_default_theme_url() ?>/images/yd-logo.gif" alt="Export2Email" width="60" height="60" border="0" style="width:60px;height:60px;"></a></td>
						<td valign="top" align="right" height="60" bgcolor="#ffffff"><h1 style="font-size:40.0pt;color:#333333;text-decoration:none;font-family:'Arial','Helvetica','sans-serif';display:inline;">YD Export2Email</h1></td>
						<td valign="top" width="15" height="60" bgcolor="#ffffff">&nbsp;</td>
					</tr>
					<tr>
						<td valign="top" width="15" height="20" bgcolor="#ffffff">&nbsp;</td>
						<td colspan="2" bgcolor="#333333" height="20"><h2 style="font-family:'Arial','Helvetica','sans-serif';font-size:12.0pt;color:white;text-decoration:none;display:inline;">Use this default template freely to build your own</h2></td>
						<td valign="top" width="15" height="20" bgcolor="#ffffff">&nbsp;</td>
					</tr>
					</thead>
					<tbody>
					<?php if (have_posts()) :?>
					<?php while (have_posts()) : the_post();?>
						<tr>
							<td width="15" bgcolor="#ffffff">&nbsp;</td>
							<td colspan="2" bgcolor="#ffffff"><a href="<?php echo get_permalink() ?>" style="text-decoration:none;"><h3 style="font-size:16.0pt;font-family:'Arial','Helvetica','sans-serif';color:#0066ff;text-decoration:none;display:inline;margin:5px 0 0 0;"><?php the_title(); ?></h3></a><br>
							<?php the_content( '&hellip;&raquo;' ); ?>
							</td>
							<td width="15" bgcolor="#ffffff">&nbsp;</td>
						</tr>
					<?php endwhile; ?>
					<?php endif; ?>
					</tbody>
					<tr>
						<td width="15" height="15" bgcolor="#ffffff">&nbsp;</td>
						<td colspan="2" height="15" bgcolor="#ffffff">&nbsp;</td>
						<td width="15" height="15" bgcolor="#ffffff">&nbsp;</td>
					</tr>
				</table>
				<br><br>
			</td>
		</tr>
	</table><br>
	<a href="<?php echo get_permalink() ?>" style="text-decoration:underline;color:#0000cc">If you cannot read this e-mail properly, please click here.</a><br>
	<i>To unsubscribe: blah.</i>
</body>
</html>