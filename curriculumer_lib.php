<?PHP
/*	OhloneHacks
 *	Mingyun Kim
 *	Daniel Tran
 *	Soyeon Wang
 *	Sair Abbas
 */

define('LOGIN_NO', 1);
define('LOGIN_USER', 2);

define('ERR_DB', '<h1>Error: Database access has failed</h1>');
define('ERR_AUTH', '<h1>Error: User authentication failed</h1>');
define('ERR_INPUT', '<h1>Error: Empty or Invalid Input Submitted</h1>');

define('TB_STUDENT', 'student');
define('TB_COURSE', 'course');
define('TB_PREREQ', 'prereq');
define('TB_OFFER', 'offer');
define('TB_MAP', 'map');

require_once 'login.php';
require_once 'curriculumer_sql.php';
require_once 'curriculumer_html.php';
require_once 'curriculumer_student.php';
require_once 'curriculumer_course.php';

ini_set('session.use_only_cookies', 1);

function sanitizeStr($conn, $var) {
	return htmlentities(strip_tags(stripslashes($conn->real_escape_string($var))));
}

function to_main($msg) {
	unset_user();
	return "<form method='post' action='curriculumer_main.php' enctype='multipart/form-data'>".
			$msg."<input type='submit' name='back' value='Back to Main'>"."</form>";
}

function login_user($conn) {
	$sid = sanitizeStr($conn, $_POST['loginid']);
	$pw = sanitizeStr($conn, $_POST['loginpw']);
	if (empty($sid) || empty($pw)) die(to_main(ERR_INPUT));
	if (!auth_user($conn, $sid, $pw)) die(to_main(ERR_AUTH));
	header("Refresh:0");
}

function auth_user($conn, $sid, $pw) {
	$tb = TB_STUDENT;
	$query = "SELECT * FROM $tb WHERE sid='$sid'";
	$result = $conn->query($query);
	if (!$result) die(to_main(ERR_DB));
	elseif ($result->num_rows) {
		$row = $result->fetch_array(MYSQLI_NUM);
		$result->close();
		if ($pw == $row[1]) {
			set_user($row[2], $row[0]);
			return true;
		}
	}
	return false;
}

function set_user($name, $user) {
	setcookie('login_auth', $name, time() + 3600 * 24, '/');
	setcookie('login_user', $user, time() + 3600 * 24, '/');
}

function unset_user() {
	setcookie('login_auth', '', time() - 3600 * 24 * 30, '/');
	setcookie('login_user', '', time() - 3600 * 24 * 30, '/');
}

function isset_user() {
	if (!isset($_COOKIE['login_auth']))
		return LOGIN_NO;
	else return LOGIN_USER;
}

/* ************************************************************************** */

function print_course_table($conn) {
	$tb = TB_COURSE;
	$query = "SELECT * FROM $tb";
	if (!($list = $conn->query($query))) die(to_main(ERR_DB));
	echo <<<_END
		<h2>Courses Required: Computer Science, General</h2>
		<table border="1" cellpadding="5" style="margin-left: 100px; border-collapse: collapse;
		text-align: center; text-valign: center;">
			<tr>
				<th>Course Code</th>
				<th>Course Name</th>
				<th>Prerequisite</th>
				<th>Unit</th>
				<th>Class Detail</th>
			</tr>
_END;
	for ($i = 0; $i < $list->num_rows; ++$i) {
		$list->data_seek($i);
		$row = $list->fetch_array(MYSQLI_NUM);
		$tb = TB_PREREQ;
		$query = "SELECT prereq FROM $tb WHERE course='$row[0]'";
		if (!($prereq = $conn->query($query))) die(to_main(ERR_DB));
		echo "<tr>
			 <td>$row[0]</td>
			 <td>$row[1]</td>
			 <td>";
		for ($j = 0; $j < $prereq->num_rows; ++$j) {
			$prereq->data_seek($j);
			$class = $prereq->fetch_array(MYSQLI_NUM);
			if ($j == 0) echo "$class[0]";
			else echo ", $class[0]";
		}
		echo "</td>
			<td>$row[3]</td>
			<td><br>
			<form method='post' action='curriculumer_main.php' enctype='multipart/form-data'>
				<input type='hidden' name='search' value='$row[0]'>
				<input type='submit' name='detail' value='Check'>
			</form>
			</td></tr>";
	}
	echo "</table>";
}

function print_course_detail($conn) {
	$course = sanitizeStr($conn, $_POST['search']);
	$tb = TB_COURSE;
	$query = "SELECT * FROM $tb WHERE code='$course'";
	if (!($result = $conn->query($query))) die(to_main(ERR_DB));
	$detail = $result->fetch_row();
	echo "<pre>
		$course - $detail[1]

		    Available: $detail[2]
		         Area: $detail[3]
		Prerequisites: ";
	$tb = TB_PREREQ;
	$query = "SELECT prereq FROM $tb WHERE course='$detail[0]'";
	if (!($prereq = $conn->query($query))) die(to_main(ERR_DB));
	if ($prereq->num_rows == 0) echo "N/A";
	for ($i = 0; $i < $prereq->num_rows; ++$i) {
		$prereq->data_seek($i);
		$class = $prereq->fetch_array(MYSQLI_NUM);
		if ($i == 0) echo "$class[0]";
		else echo ", $class[0]";
	}
	echo "<br>";
	echo "			 Unit: $detail[4]
		 Transferable: $detail[5]
	   Course Description: $detail[6]
		</pre>";

	$tb = TB_OFFER;
	$query = "SELECT * FROM $tb WHERE course='$course'";
	if (!($offer = $conn->query($query))) die(to_main(ERR_DB));
	echo <<<_END
		<h2 style='text-indent:100px;'>2020 FA Course Offer</h2>
		<table border="1" cellpadding="5" style="margin-left: 100px; border-collapse: collapse;
		text-align: center; text-valign: center;">
			<tr>
				<th>Section</th>
				<th>Schedule</th>
				<th>Instructor</th>
			</tr>
_END;
	for ($i = 0; $i < $offer->num_rows; ++$i) {
		$offer->data_seek($i);
		$row = $offer->fetch_array(MYSQLI_NUM);
		echo "<tr>
			 <td>$row[1]</td>
			 <td>$row[2]</td>
			 <td>$row[3]</td>
			 </tr>";
	}
	echo "</table>";
}

function print_course_map($conn) {
	echo "<img src='map.png'>";
}

function print_profile($conn) {
	$tb = TB_STUDENT;
	$query = "SELECT * FROM $tb WHERE sid='$_COOKIE[login_user]'";
	if (!($profile = $conn->query($query))) die(to_main(ERR_DB));
	$student = $profile->fetch_row();
	echo "<h2 style='text-indent:100px;'>Student Profile</h2>";
	echo "<pre>
		      Name: $_COOKIE[login_auth]
		Student ID: $_COOKIE[login_user]
		     Major: $student[4]
		       GPA: $student[5]
		</pre>";
}
?>
