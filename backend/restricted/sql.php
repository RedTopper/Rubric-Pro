<?php
if(!isset($needsSQL)) die();

/**
 * Selects all students based on a teacher
 *
 * $teacherNum: The teacher's number in the database.
 */
function getStudents($teacherNum) {
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
function getStudentsBasedOnSearch($teacherNum, $location, $searchTerm) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME
FROM STUDENT
JOIN TEACHES ON STUDENT.NUM = TEACHES.STUDENT_NUM
WHERE
TEACHES.TEACHER_NUM = :teacherID AND
$location LIKE CONCAT('%',:search,'%') 
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('teacherID' => $teacherNum, 'search' => $searchTerm));	
	return $stmt->fetchAll();
}