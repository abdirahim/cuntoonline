<!DOCTYPE html>
<html lang="en">
	<head>
	</head>
	<body id="body" style="margin:0; padding:0;" >
	<table width="1000px" align="center" cellpadding="0" cellspacing="0" style="background-color:#fff;">
		<tbody>
			<tr>
				<td style="background-color:#f7f7f7"><a href="{{ URL::to('/') }}"><img src="{{ config("constants.WEBSITE_IMG_URL") }}logo.png" alt="logo"></a></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td><?php echo $messageBody; ?></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr style="margin-top:20px;">
				<td style="background-color:#262626"><p style="color:#9a9a9a">&copy; {{ Config::get('Site.copyright_text')}}</p> </td>
			</tr>
		</tbody>
	</table>
	</body>
</html>
