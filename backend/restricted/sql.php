<?php
if(!isset($needsSQL)) die();

/**
 * Selects all students based on a teacher
 *
 * $teacherNum: The teacher's number in the database.
 */
function getAllStudents($teacherNum) {
	global $conn;
	$stmt = $conn->prepare( 
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME 
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE 
TEACHES.TEACHER_NUM = :teacherID
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('teacherID' => $teacherNum));	
	return $stmt->fetchAll();
}

/**
 * Selects all students based on a teacher and search term.
 *
 * $teacherNum: The teacher's number in the database.
 * $location: Either "STUDENT.FIRST_NAME", "STUDENT.LAST_NAME", or "STUDENT.USERNAME".
 * $searchTerm: The term to search for in the database. 
 */
function getAllStudentsBasedOnSearch($teacherNum, $location, $searchTerm) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE
TEACHES.TEACHER_NUM = :teacherNum AND
$location LIKE CONCAT('%',:search,'%') 
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('teacherNum' => $teacherNum, 'search' => $searchTerm));	
	return $stmt->fetchAll();
}

/**
 * Checks if the passed username already exists within the teachers database
 *
 * $username: Username to check.
 * return: True if it exists, false otherwise.
 */
function isUsernameInTeacherDatabase($username) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT USERNAME
FROM TEACHER
WHERE
USERNAME = :username
SQL
	);
	$stmt->execute(array('username' => $username));
	return $stmt->rowCount() > 0;
}

/**
 * Checks if the passed username already exists within the students database
 *
 * $username: Username to check.
 * $studentNum: The number of the student if it exists. This variable is always modified by the function.
 * return: True if it exists, false otherwise.
 */
function isUsernameInStudentDatabase($username, &$studentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, USERNAME 
FROM STUDENT 
WHERE 
USERNAME = :username
SQL
	);
	$stmt->execute(array('username' => $username));
	if($stmt->rowCount() > 0) {
		$row = $stmt->fetch();
		$studentNum = $row["NUM"];
		return true;
	} else {
		$studentNum = null;
		return false;
	}
}

/**
 * Checks if the passed teacher number and student number are already linked.
 *
 * $teacherNum: The teacher's number in the database.
 * $studentNum: The student's number in the database.
 * return: True if the teacher and student are linked together.
 */
function isTeacherAndStudentLinked($teacherNum, $studentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT STUDENT_NUM, TEACHER_NUM 
FROM TEACHES 
WHERE 
STUDENT_NUM = :student AND 
TEACHER_NUM = :teacher
SQL
	);
	$stmt->execute(array('student' => $studentNum, 'teacher' => $teacherNum));
	return $stmt->rowCount() > 0;
}

/**
 * Gets all information about a student
 *
 * $studentNum: The number of the student in the database.
 * return: An SQL row of all of the student information.
 */
function getStudentInformation($studentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT
NUM, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME, NICK_NAME, GRADE, EXTRA
FROM STUDENT
WHERE NUM = :student
SQL
	);
	$stmt->execute(array('student' => $studentNum));
	return $stmt->fetch();
}

/**
 * Creates a student
 *
 * $username: The username of the student
 * $first: The first name of the student
 * $last: The last name of the student
 * $nick: The nickname of the student
 * $grade: The grade (year) of the student
 * $extra: Extra information about the student
 */
function createStudent($username, $first, $last, $nick, $grade, $extra) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO STUDENT 
(USERNAME, PASSWORD, FIRST_NAME, LAST_NAME, NICK_NAME, GRADE, EXTRA, SETTINGS)
VALUES
(:username, :password, :first, :last, :nick, :grade, :extra, :settings)
SQL
	);
	$stmt->execute(array(
	'username' => $username, 
	'password' => "CHANGE", 
	'first' => $first, 
	'last' => $last, 
	'nick' => $nick, 
	'grade' => $grade, 
	'extra' => $extra, 
	'settings' => "{}"));
}

/**
 * Links a student to a teacher account.
 *
 * $teacherNum: The number of the teacher in the database
 * $studentNum: The number of the student in the database
 */
function linkTeacherToStudent($teacherNum, $studentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO TEACHES
(TEACHER_NUM, STUDENT_NUM)
VALUES
(:teacher, :student)
SQL
	);
	$stmt->execute(array(
	'teacher' => $teacherNum, 
	'student' => $studentNum));
}

/**
 * Checks if the number of the student in the database and 
 * the username are of the same record. Used for client varification
 *
 * $username: The username of the student.
 * $studentNum: The probable number of the student. 
 * return: True if the username and number match.
 */
function doesStudentUsernameAndNumberMatch($username, $studentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, USERNAME
FROM STUDENT 
WHERE 
USERNAME = :username AND
NUM = :num
SQL
	);
	$stmt->execute(array('username' => $username, 'num' => $studentNum));
	return $stmt->rowCount() > 0;
}

/**
 * Gets the list of classes that a student belongs to limited by
 * a single teacher
 *
 * $teacherNum: The number of a teacher.
 * $studentNum: The number of the student.
 * return: The list of classes in SQL form if they exist, or null.
 */
function getListOfStudentClassesViaTeacher($teacherNum, $studentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT CLASS.NUM, CLASS.NAME, CLASS.YEAR, CLASS.PERIOD, CLASS.TERM, CLASS.DESCRIPTOR
FROM `CLASS-STUDENT_LINKER` CSL, CLASS
WHERE
CSL.STUDENT_NUM = :studentNum AND 
CSL.CLASS_NUM = CLASS.NUM AND
CLASS.TEACHER_NUM = :teacherNum
ORDER BY YEAR DESC, TERM DESC, PERIOD
SQL
	);
	$stmt->execute(array('studentNum' =>  $studentNum, 'teacherNum' => $teacherNum));
	
	#If there is at least one class....
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}