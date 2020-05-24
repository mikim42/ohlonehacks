<?PHP
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die(to_main(ERR_DB));

$tb = TB_STUDENT;
$query = "CREATE TABLE $tb (
	sid VARCHAR(64) NOT NULL UNIQUE,
	pw VARCHAR(64) NOT NULL,
	name VARCHAR(64) NOT NULL,
	school VARCHAR(32) NOT NULL,
	major VARCHAR(32) NOT NULL,
	gpa FLOAT NOT NULL
)";
$conn->query($query);
$query = "DESCRIBE $tb";
$result = $conn->query($query);
if (!$result) die(to_main(ERR_DB));
$result->close();

$tb = TB_COURSE;
$query = "CREATE TABLE $tb (
	code VARCHAR(16) NOT NULL,
	name VARCHAR(128) NOT NULL,
	offer VARCHAR(32) NOT NULL,
	area VARCHAR(16) NOT NULL,
	unit INT NOT NULL,
	transfer CHAR(1) NOT NULL,
	description VARCHAR(1024) NOT NULL
)";
$conn->query($query);
$query = "DESCRIBE $tb";
$result = $conn->query($query);
if (!$result) die(to_main(ERR_DB));
$result->close();

$tb = TB_PREREQ;
$query = "CREATE TABLE $tb (
	course VARCHAR(16) NOT NULL,
	prereq VARCHAR(16) NOT NULL
)";
$conn->query($query);
$query = "DESCRIBE $tb";
$result = $conn->query($query);
if (!$result) die(to_main(ERR_DB));
$result->close();

$tb = TB_OFFER;
$query = "CREATE TABLE $tb (
	course VARCHAR(16) NOT NULL,
	section INT NOT NULL,
	time VARCHAR(32) NOT NULL,
	instructor VARCHAR(32) NOT NULL
)";
$conn->query($query);
$query = "DESCRIBE $tb";
$result = $conn->query($query);
if (!$result) die(to_main(ERR_DB));
$result->close();

$tb = TB_MAP;
$query = "CREATE TABLE $tb (
	sid VARCHAR(16) NOT NULL,
	name VARCHAR(16) NOT NULL,
	course VARCHAR(16) NOT NULL,
	stat INT NOT NULL
)";
$conn->query($query);
$query = "DESCRIBE $tb";
$result = $conn->query($query);
if (!$result) die(to_main(ERR_DB));
$result->close();

$query = "insert into student values('123456789', '1234', 'John Snow', 'Ohlone College', 'Computer Science', 3.5);
insert into course values('CS-1','Intro to Programming C++', 'FALL/SUMMER/SPRING', 'CS Major', 3, 'Y', 'CS-1 course description');
insert into course values('CS-2', 'Object-Oriented Programming C++', 'FALL/SPRING','CS Major', 4, 'N', 'CS-2 course description');
insert into course values('CS-3', 'Intro to Data Structures', 'FALL/SPRING', 'CS Major', 3, 'Y', 'CS-3 course description');
insert into course values('CS-12', 'Assembly Language', 'SPRING', 'CS Major', 3, 'Y', 'CS-12 course description');
insert into course values('CS-3A', 'Discrete Structures', 'FALL/SPRING', 'CS Major', 3, 'Y', 'CS-3A course description');
insert into course values('MATH-1', 'Intro to Linear Algebra', 'FALL', 'STEM General', 5, 'N', 'MATH-1 course description');
insert into course values('MATH-2', 'Differential Equations', 'SPRING', 'STEM General', 5, 'Y', 'MATH-2 course description');
insert into course values('PHYS-1', 'Mechanics', 'ENGR Major', 'FALL', 4, 'Y', 'PHYS-1 course description');
insert into course values('PHYS-2', 'Electricity & Magnetism', 'SPRING', 'ENGR Major', 4, 'Y', 'PHYS-2 course description');
insert into prereq values('CS-2', 'CS-1');
insert into prereq values('CS-3', 'CS-1');
insert into prereq values('CS-12', 'CS-2');
insert into prereq values('CS-3A', 'CS-2');
insert into prereq values('MATH-1', 'MATH-A');
insert into prereq values('MATH-2', 'MATH-1');
insert into prereq values('PHYS-1', 'MATH-2');
insert into prereq values('PHYS-2', 'PHYS-1');
insert into offer values('CS-1', 1, 'MW 1:00PM - 2:15PM', 'Mingyun');
insert into offer values('CS-1', 2, 'MW 3:00PM - 4:15PM', 'Soyeon');
insert into offer values('CS-1', 3, 'TH 6:30PM - 7:45PM', 'Daniel');";
$conn->multi_query($query);
?>
