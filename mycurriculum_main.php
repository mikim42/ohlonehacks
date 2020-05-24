<?PHP
/*	OhloneHacks
 *	Mingyun Kim
 *	Daniel Tran
 *	Soyeon Wang
 *	Sair Abbas
 */

require_once 'mycurriculum_lib.php';

html_header();
$curr_user = isset_user();

/* ************************************************************************** */

if ($_FILES) {
	if ($_FILES['filename']['type'] != 'image/jpeg') die(to_main(ERR_INPUT));
	$fname = mysql_entities_fix_string($conn, $_FILES['filename']['name']);
	$text = mysql_entities_fix_string($conn, $_POST['text']);
	$user = $_COOKIE['login_user'];
	if (!file_exists($user)) mkdir($user, 0777, true);
	$fname = $user.'/'.$fname;
	move_uploaded_file($_FILES['filename']['tmp_name'], $fname);
	if (!file_exists($fname)) die(to_main(ERR_INPUT));
	if (!add_post($conn, $user, $text, $fname)) {
		die(to_main(ERR_DB));
		unlink($fname);
	}
}

if (isset($_POST['logout'])) die(to_main("Successfully Signed Out<br>"));
if (isset($_POST['login'])) login_user($conn);

if ($curr_user == LOGIN_NO) {
	html_login();
}
else if ($curr_user == LOGIN_USER) {
	$username = $_COOKIE['login_auth'];
	echo "<h2 style='text-indent:100px;'>Welcome, $username!</h2>";
	echo '<br>';
	html_main_opt();
	html_logout();
	echo '<hr>';
}

if (isset($_POST['detail'])) {
	print_course_detail($conn);
	echo "<hr>";
}

if (isset($_POST['search'])) {
	print_course_table($conn);
}
else if (isset($_POST['map'])) {
	print_course_map($conn);
}
else if (isset($_POST['profile'])) {
	print_profile($conn);

}

/* ************************************************************************** */

html_footer();
$conn->close();
?>
