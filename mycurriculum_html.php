<?PHP
function html_header() {
	echo <<<_END
<html>
	<head>
		<title>MyCurriculum</title>
	</head>
	<body>
		<h1>
			OhloneHacks - MyCurriculum <br>
			Mingyun Kim <br>
			Daniel Tran <br>
			Soyeon Wang <br>
			Sair Abbas
		</h1>
		<h2>This Website Requires Cookies</h2>
		<hr>
		<h1 style="text-indent:165px;">mycurriculum Backend Demo</h1>
_END;
}

function html_footer() {
	echo <<<_END
	</body>
</html>
_END;
}

function html_login() {
	echo <<<_END
			<h2 style="text-indent:250px;">Login</h2>
		<form method='post' action='mycurriculum_main.php' enctype='multipart/form-data'>
			<pre>
		Student ID : <input size='40' maxlength='32' type='text' name='loginid'><br>
		  Password : <input size='40' maxlength='32' type='password' name='loginpw'><br>
				<input type='submit' name='login' value='Sign In'>
			</pre>
		</form>
_END;
}

function html_logout() {
	echo <<<_END
	<div style="margin-left:250px";>
	<form method='post' action='mycurriculum_main.php' enctype='multipart/form-data'>
		<input type='submit' name='logout' value='Sign Out'>
	</form>
	</div>
_END;
}

function html_main_opt() {
	echo <<<_END
		<form method='post' action='mycurriculum_main.php' enctype='multipart/form-data'>
			<pre>
			<input type='submit' style='width: 125px; font-size: 24;' name='search' value='Search Classes'><br>
			<input type='submit' style='width: 125px; font-size: 24;' name='map' value='Course Map'><br>
			<input type='submit' style='width: 125px; font-size: 24;' name='profile' value='Profile'><br>
			</pre>
		</form>
_END;
}
?>
