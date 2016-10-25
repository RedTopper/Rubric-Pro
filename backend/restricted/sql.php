<?php
if(!isset($needsSQL)) die();

/**
 * Selects all students based on a teacher
 *
 * $teacherNum: The teacher's number in the database.
 */
function sql_getAllStudents($teacherNum) {
	global $conn;
	$stmt = $conn->prepare( 
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME 
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE 
TEACHES.TEACHER_NUM = :teacherNum
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('teacherNum' => $teacherNum));	
	return $stmt->fetchAll();
}

/**
 * Selects all students based on a teacher and search term.
 *
 * $teacherNum: The teacher's number in the database.
 * $location: Either "STUDENT.FIRST_NAME", "STUDENT.LAST_NAME", or "STUDENT.USERNAME".
 * $searchTerm: The term to search for in the database. 
 */
function sql_getAllStudentsBasedOnSearch($teacherNum, $location, $searchTerm) {
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
function sql_isUsernameInTeacherDatabase($username) {
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
function sql_isUsernameInStudentDatabase($username, &$studentNum) {
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
function sql_isTeacherAndStudentLinked($teacherNum, $studentNum) {
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
function sql_getStudentInformation($studentNum) {
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
function sql_createStudent($username, $first, $last, $nick, $grade, $extra) {
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
 * $studentNum: The number of the student in the database
 * $teacherNum: The number of the teacher in the database we want to link the student to
 */
function sql_bindStudentToTeacher($studentNum, $teacherNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO TEACHES
(TEACHER_NUM, STUDENT_NUM)
VALUES
(:teacher, :student)
SQL
	);
	$stmt->execute(array('teacher' => $teacherNum, 'student' => $studentNum));
}

/**
 * This function will attempt to disconnect a student from a teacher account
 *
 * $studentNum: The number of the student in the database
 * $teacherNum: The number of the teacher in the database we want to disconnect the student from
 */
function sql_unbindStudentFromTeacher($studentNum, $teacherNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
DELETE FROM TEACHES 
WHERE 
STUDENT_NUM = :studentnum AND
TEACHER_NUM = :teachernum
SQL
	);
	$stmt->execute(array('studentnum' => $studentNum, 'teachernum' => $teacherNum));
}

/**
 * Binds a student to a class
 *
 * $studentNum: The number of the student in the database
 * $teacherNum: The number of the class in the database we want to bind the student to.
 */
function sql_bindStudentToClass($studentNum, $classNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO `CLASS-STUDENT_LINKER` 
(STUDENT_NUM, CLASS_NUM) 
VALUES
(:studentnum, :classnum)
SQL
	);
	$stmt->execute(array('studentnum' => $studentNum, 'classnum' => $classNum));
}


/**
 * Attempts to unlink a student from a class.
 *
 * $studentNum: The number of student we want to unlink
 * $classNum: The number of the class we want to unlink the student from.
 */ 
function sql_unbindStudentFromClass($studentNum, $classNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
DELETE FROM `CLASS-STUDENT_LINKER` 
WHERE STUDENT_NUM = :studentnum AND 
CLASS_NUM = :classnum
SQL
	);
	$stmt->execute(array('studentnum' => $studentNum, 'classnum' => $classNum));
}

/**
 * Checks to see if a student already exists within a class.
 *
 * $studentNum: The number of the student in the database
 * $classNum: The number of the class in the database
 * Returns true 
 */
function sql_doesStudentAlreadyExistInClass($studentNum, $classNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT STUDENT_NUM, CLASS_NUM 
FROM `CLASS-STUDENT_LINKER`
WHERE 
STUDENT_NUM = :studentnum AND
CLASS_NUM = :classnum
SQL
	);
	$stmt->execute(array('studentnum' => $studentNum, 'classnum' => $classNum));
	return $stmt->rowCount() > 0;
}

/**
 * Checks if the number of the student in the database and 
 * the username are of the same record. Used for client varification
 *
 * $username: The username of the student.
 * $studentNum: The probable number of the student. 
 * return: True if the username and number match.
 */
function sql_doesStudentUsernameAndNumberMatch($username, $studentNum) {
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
function sql_getListOfStudentClassesViaTeacher($teacherNum, $studentNum) {
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
	
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}

/**
 * Gets a list of classes that belong to a specific teacher.
 *
 * $teacherNum: The number of a teacher.
 * return: A 2D array of all classes that belong to the teacher.
 */
function sql_getListOfClassesViaTeacher($teacherNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, NAME, YEAR, TERM, PERIOD, DESCRIPTOR
FROM CLASS
WHERE
TEACHER_NUM = :teacherNum
ORDER BY YEAR DESC, TERM DESC, PERIOD
SQL
	);
	$stmt->execute(array('teacherNum' => $_SESSION["NUM"]));	
	return $stmt->fetchAll();
}

/**
 * Resets a student's password
 *
 * $studentNum: The number of the student in the database to reset.
 */
function sql_resetStudentPassword($studentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
UPDATE STUDENT 
SET PASSWORD='CHANGE' 
WHERE 
NUM=:num
SQL
	);
	$stmt->execute(array('num' => $studentNum));
}

/**
 * Verifies if a class actually belongs to a specified teacher.
 *
 * $teacherNum: The teacher we are verifying matches a class
 * $classNum: The class we are verifying matches a teacher
 * $className: This function modifies this variable to contain the selected class name. Null if no class matches.
 * return: True if the teacher owns the class, false otherwise.
 */
function sql_doesTeacherOwnClass($teacherNum, $classNum, &$className) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, NAME
FROM CLASS
WHERE
TEACHER_NUM = :teacherNum AND 
NUM = :classNum
SQL
	);
	$stmt->execute(array('teacherNum' => $teacherNum, 'classNum' =>  $classNum));
	if($stmt->rowCount() > 0) {
		$className = $stmt->fetch()["NAME"];
		return true;
	} else {
		$className = "";
		return false;
	}
}

/**
 * Creates a new class using the information provided.
 *
 * $teacherNum: The number of the teacher in the database.
 * $className: A name for the class
 * $classYear: The year (numerical, date) of the class
 * $period: The period the class occurs
 * $term: The term the class occurs
 * $descriptor: A description of the class.
 */
function sql_createClass($teacherNum, $className, $classYear, $period, $term, $descriptor) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO CLASS
(TEACHER_NUM, NAME, YEAR, PERIOD, TERM, DESCRIPTOR)
VALUES
(:teachernum, :classname, :year, :period, :term, :descriptor)
SQL
	);
	$stmt->execute(array(
	'teachernum' => $teacherNum,
	'classname' => $className,
	'year' => $classYear,
	'period' => $period,
	'term' => $term,
	'descriptor' => $descriptor));	
}