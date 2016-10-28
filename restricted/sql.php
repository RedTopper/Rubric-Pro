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

function sql_bindRubricToAssignment($rubricNum, $assignmentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO `ASSIGNMENT-RUBRIC_LINKER`
(RUBRIC_NUM, ASSIGNMENT_NUM)
VALUES
(:rubricnum, :assignmentnum)
SQL
	);
	$stmt->execute(array('rubricnum' => $rubricNum, 'assignmentnum' => $assignmentNum));
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

function sql_doesRubricAlreadyExistInAssignment($rubricNum, $assignmentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT RUBRIC_NUM, ASSIGNMENT_NUM 
FROM `ASSIGNMENT-RUBRIC_LINKER`
WHERE 
RUBRIC_NUM = :rubricnum AND
ASSIGNMENT_NUM = :assnnum
SQL
	);
	$stmt->execute(array('rubricnum' => $rubricNum, 'assnnum' => $assignmentNum));
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
 * Gets a list of students that are in a class.
 *
 * $classNum: The number of the class in the database.
 * return: All of the students in the class or null if there are none.
 */
function sql_getListOfStudentsViaClass($classNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT STUDENT.NUM, STUDENT.USERNAME, STUDENT.FIRST_NAME, STUDENT.LAST_NAME, STUDENT.NICK_NAME, STUDENT.GRADE, STUDENT.EXTRA
FROM STUDENT, `CLASS-STUDENT_LINKER` CSL
WHERE
CSL.CLASS_NUM = :classNum AND 
CSL.STUDENT_NUM = STUDENT.NUM
ORDER BY STUDENT.LAST_NAME, STUDENT.FIRST_NAME
SQL
	);
	$stmt->execute(array('classNum' => $classNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
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
 * return: A row of the matched class if found, or null otherwise.
 */
function sql_doesTeacherOwnClass($teacherNum, $classNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, NAME, YEAR, TERM, PERIOD, DESCRIPTOR
FROM CLASS
WHERE
TEACHER_NUM = :teacherNum AND 
NUM = :classNum
SQL
	);
	$stmt->execute(array('teacherNum' => $teacherNum, 'classNum' =>  $classNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetch();
	} else {
		return null;
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

/**
 * Gets all of the ROOT components that belong to a teacher.
 * 
 * $teacherNum: The number of the teacher in the database.
 * return: All of the components selected from the teacher or null if none are selected.
 */
function sql_getAllRootComponents($teacherNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION 
FROM COMPONENT 
WHERE 
TEACHER_NUM = :teacherNum AND 
PARENT_NUM IS NULL
SQL
	);
	$stmt->execute(array('teacherNum' => $teacherNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}

/**
 * Gets the information of a component from a teacher.
 *
 * $teacherNum: The number of the teacher in the database.
 * $componentNum: The number of the component in the database to fetch.
 * return: The component information or null if it does not exist.
 */
function sql_getComponent($teacherNum, $componentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION
FROM COMPONENT
WHERE 
TEACHER_NUM = :teacherNum AND
NUM = :componentNum
SQL
	);
	$stmt->execute(array('teacherNum' => $teacherNum, 'componentNum' => $componentNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetch();
	} else {
		return null;
	}
}

/**
 * Gets all of the components that are children of a specified component.
 *
 * $teacherNum: The number of the teacher in the database.
 * $componentNum: The parent number of the component in the database to use to fetch the children.
 * return: The component information or null if it does not exist.
 */
function sql_getAllSubComponentsFromComponent($teacherNum, $componentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION
FROM COMPONENT 
WHERE 
TEACHER_NUM = :teacherNum AND
PARENT_NUM = :componentNum 
ORDER BY SYMBOL
SQL
	);
	$stmt->execute(array('teacherNum' => $teacherNum, 'componentNum' => $componentNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}

/**
 * Creates a new root component bound to a teacher.
 * 
 * $teacherNum: The number of the teacher in the database.
 * $symbol: The symbol of the component. A symbol is something like "I", "B", "7", "a", "ii", "SCI" etc.
 * $name: The name of the component
 * $description: A long description of the component.
 */
function sql_createRootComponent($teacherNum, $symbol, $name, $description) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO COMPONENT
(TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION)
VALUES
(:teacherNum, NULL, :symbol, :name, :description)
SQL
	);
	$stmt->execute(array(
	'teacherNum' => $teacherNum, 
	'symbol' => $symbol, 
	'name' => $name,
	'description' => $description));
}

/**
 * Creates a new component bound to a teacher and bound to another component.
 *
 * $teacherNum: The number of the teacher in the database.
 * $parentComponentNum: The number of the parent component. Must be set. Use sql_createRootComponent() otherwise.
 * $symbol: The symbol of the component as described in sql_createRootComponent()
 * $name: The name of the component
 * $description: A long description of the component.
 */
function sql_createComponent($teacherNum, $parentComponentNum, $symbol, $name, $description) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO COMPONENT
(TEACHER_NUM, PARENT_NUM, SYMBOL, NAME, DESCRIPTION)
VALUES
(:teacherNum, :parentNum, :symbol, :name, :description)
SQL
	);
	$stmt->execute(array(
	'teacherNum' => $teacherNum, 
	'parentNum' => $parentComponentNum,
	'symbol' => $symbol, 
	'name' => $name,
	'description' => $description));
}

/**
 * Gets all of the rubrics bound to a teacher. This query also includes the max
 * possible points of a rubric.
 *
 * $teacherNum: The number of the teacher in the database.
 */
function sql_getAllRubricsFromTeacher($teacherNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, MAX_POINTS_PER_CRITERIA, SUBTITLE, ((
	SELECT COUNT(*) 
	FROM RUBRIC_CRITERIA
	WHERE
	TEACHER_NUM = :teachernum AND
	RUBRIC_NUM = RUBRIC.NUM) * MAX_POINTS_PER_CRITERIA) AS TOTAL_POINTS 
FROM RUBRIC
WHERE
TEACHER_NUM = :teachernum
ORDER BY SUBTITLE
SQL
	);
	$stmt->execute(array('teachernum' => $teacherNum));	
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}

/**
 * Checks to see if a rubric actually belongs to a teacher.
 *
 * $teacherNum: The number of the teacher in the database.
 * $rubricNum: The number of the rubric that we are checking if it belongs to the teacher.
 * return: The rubric row if it matches, or null if it does not.
 */
function sql_doesTeacherOwnRubric($teacherNum, $rubricNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, MAX_POINTS_PER_CRITERIA, SUBTITLE
FROM RUBRIC
WHERE
TEACHER_NUM = :teachernum AND
NUM = :num
SQL
	);
	$stmt->execute(array('teachernum' => $teacherNum, 'num' => $rubricNum));	
	if($stmt->rowCount() > 0) {
		return $stmt->fetch();
	} else {
		return null;
	}
}

function sql_doesTeacherOwnAssignment($teacherNum, $assignmentNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, TITLE, DESCRIPTION
FROM ASSIGNMENT
WHERE
TEACHER_NUM = :teachernum AND
NUM = :num
SQL
	);
	$stmt->execute(array('teachernum' => $teacherNum, 'num' => $assignmentNum));	
	if($stmt->rowCount() > 0) {
		return $stmt->fetch();
	} else {
		return null;
	}
}

/**
 * Creates a new rubric bound to a teacher
 *
 * $teacherNum: The number of the teacher in the database.
 * $maxPointsPerCriteria: The absolute maximum amount of points that a student may be awarded per criteria in this rubric.
 * $subtitle: The title of the rubric.
 */
function sql_createRubric($teacherNum, $maxPointsPerCriteria, $subtitle) {
	global $conn;
	$stmt = $conn->prepare(<<<SQL
INSERT INTO RUBRIC
(TEACHER_NUM, MAX_POINTS_PER_CRITERIA, SUBTITLE)
VALUES
(:teacherNum, :points, :sub)
SQL
	);
	$stmt->execute(array(
	'teacherNum' => $teacherNum, 
	'points' => $maxPointsPerCriteria,
	'sub' => $subtitle));
}

/**
 * Fetches all qualities that are bound to a rubric.
 *
 * $rubricNum: Numbe of the rubric in the database.
 * $qualitiesCount: Passed by reference. This method modifies this variable to contain the amount of selected qualities.
 * returns: Either a list of all of the qualities in SQL format or null if there are none. 
 */
function sql_getAllQualitiesInRubric($rubricNum, &$qualitiesCount) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, POINTS, QUALITY_TITLE
FROM RUBRIC_QUALITY
WHERE
RUBRIC_NUM = :rubric
ORDER BY POINTS
SQL
	);
	$stmt->execute(array('rubric' => $rubricNum));
	$qualitiesCount = $stmt->rowCount();
	if($qualitiesCount > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}

/**
 * Fetches all of the criteria of a rubric.
 *
 * $rubricNum: Numbe of the rubric in the database.
 * returns: Either a list of all of the qualities in SQL format or null if there are none. 
 */
function sql_getAllCriteriaInRubric($rubricNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, CRITERIA_TITLE 
FROM RUBRIC_CRITERIA 
WHERE 
RUBRIC_NUM = :rubric
SQL
	);
	$stmt->execute(array('rubric' => $rubricNum));	
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}

/**
 * Creates and initializes a quality.
 * This method will create empty cells in the rubric so they can be used later.
 *
 * $rubricNum: Numbe of the rubric in the database.
 * $points: The points awarded to the quality.
 * $qualityTitle: The title of the quality for the rubric (will appear at the top)
 */
function sql_createQuality($rubricNum, $points, $qualityTitle) {
	global $conn;
	#First, insert the quality as normal. We'll use this to get the number we just inserted.
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO RUBRIC_QUALITY 
(RUBRIC_NUM, POINTS, QUALITY_TITLE) 
VALUES 
(:rubric, :points, :title)
SQL
	);
	$stmt->execute(array(
	'rubric' => $rubricNum,
	'points' => $points,
	'title' => $qualityTitle));
	$qualityNum = $conn->lastInsertId();

	#Because we are adding a quality, we need to initialize a cell for every criteria.
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM 
FROM RUBRIC_CRITERIA 
WHERE RUBRIC_NUM = :rubric
SQL
	);
	$stmt->execute(array('rubric' => $rubricNum));
	$criteria = $stmt->fetchAll();

	#Now that we have the inserted id and each criteria, let's initialize the cells.
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO RUBRIC_CELL 
(RUBRIC_CRITERIA_NUM, RUBRIC_QUALITY_NUM, CONTENTS) 
VALUES 
(:criteria, :quality, '')
SQL
	);

	#And we'll insert in a foreach loop.
	foreach($criteria as $criterion) {
		$stmt->execute(array(
		'criteria' => $criterion["NUM"],
		'quality' => $qualityTitle));
	}
}

/**
 * Creates and initializes a criteria.
 * This method will create empty cells in the rubric so they can be used later.
 *
 * $rubricNum: Numbe of the rubric in the database.
 * $criteriaTitle: The title of the criteria for the rubric (will appear on the left side)
 */
function sql_createCriteria($rubricNum, $criteriaTitle) {
	global $conn;
	#You can see the steps of quality submit for more details.
	#Basically, insert....... 
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO RUBRIC_CRITERIA 
(RUBRIC_NUM, CRITERIA_TITLE) 
VALUES 
(:rubric, :title)
SQL
	);
	$stmt->execute(array(
	'rubric' => $rubricNum,
	'title' => $criteriaTitle));
	$criterion = $conn->lastInsertId();

	#......then select the qualities.......
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM 
FROM RUBRIC_QUALITY 
WHERE RUBRIC_NUM = :rubric
SQL
	);
	$stmt->execute(array('rubric' => $rubricNum));
	$qualities = $stmt->fetchAll();

	#......and initialize the cells.....
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO RUBRIC_CELL 
(RUBRIC_CRITERIA_NUM, RUBRIC_QUALITY_NUM, CONTENTS) 
VALUES 
(:criteria, :quality, '')
SQL
	);

	#......in a foreach loop.
	foreach($qualities as $quality) {
		$stmt->execute(array(
		'criteria' => $criterion,
		'quality' => $quality["NUM"]));
	}
}

/**
 * Gets a criteria information based off of a criteria number.
 *
 * $criteriaNum: The number of the criteria in the database.
 * return: A row of one selected criteria or null if none is found.
 */
function sql_getCriteria($criteriaNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT RUBRIC_NUM 
FROM RUBRIC_CRITERIA 
WHERE
NUM = :criteria
SQL
	);
	$stmt->execute(array('criteria' => $criteriaNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetch();
	} else {
		return null;
	}
}

/**
 * Gets a single rubric information. 
 *
 * $teacherNum: The number of the teacher in the database.
 * $rubricNum: The number of the rubric to obtain information about.
 * return: Either the selected rubric information or null if there is no match.
 */
function sql_getRubric($teacherNum, $rubricNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT TEACHER_NUM 
FROM RUBRIC 
WHERE 
NUM = :rubric AND 
TEACHER_NUM = :teacher
SQL
	);
	$stmt->execute(array('rubric' => $rubricNum, "teacher" => $teacherNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetch();
	} else {
		return null;
	}
}

/**
 * Fetch all of the criteria that is binded to a component.
 *
 * $criteriaNum: The number of the criteria in the database.
 * return: All off the bound component NUMBERS (not the contents!)
 */
function getAllCriteriaComponents($criteriaNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT COMPONENT_NUM 
FROM CRITERION 
WHERE RUBRIC_CRITERIA_NUM = :criteria
SQL
	);
	$stmt->execute(array('criteria' => $criteriaNum));
	return $stmt->fetchAll();
}

/**
 * Obtains every single cell of a rubric in a single line. 
 * To format the cells, you'll need to know the width (amount of qualities) of the rubric.
 * This method orders the cells by the points earned per quality automatically.
 *
 * $rubricNum: The number of the rubric to obtain the cells of.
 */
function sql_getAllRubricCells($rubricNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT RUBRIC_CRITERIA_NUM, RUBRIC_QUALITY_NUM, CONTENTS
FROM RUBRIC_CELL, RUBRIC_CRITERIA, RUBRIC_QUALITY
WHERE
RUBRIC_CRITERIA.RUBRIC_NUM = :rubric AND
RUBRIC_QUALITY.RUBRIC_NUM = :rubric AND
RUBRIC_CELL.RUBRIC_QUALITY_NUM = RUBRIC_QUALITY.NUM AND
RUBRIC_CELL.RUBRIC_CRITERIA_NUM = RUBRIC_CRITERIA.NUM
ORDER BY RUBRIC_CRITERIA_NUM, RUBRIC_QUALITY.POINTS
SQL
	);
	$stmt->execute(array('rubric' => $rubricNum));
	return $stmt->fetchAll();
}

/**
 * Obtains a list of all the pre compiled symbol trees for a criteria.
 *
 * $criteriaNum: The criteria we would like to fetch the symbol trees from.
 * return: An array of all of the compiled symbol trees. (may be empty)
 */
function sql_getAllCompiledSymbolTreesFromCriteria($criteriaNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT COMPILED_SYMBOL_TREE 
FROM CRITERION 
WHERE 
RUBRIC_CRITERIA_NUM = :criteria
SQL
	);
	$stmt->execute(array('criteria' => $criteriaNum));
	return $stmt->fetchAll();
}

/**
 * Gets a specific rubric cell. Usefull for updating a specific cell in a rubric.
 *
 * $rubricCriteriaNum: The "y" cordnate of the cell in a rubric.
 * $rubricQualityNum: The "x" cordnate of a cell in a rubric.
 */
function sql_getRubricCell($rubricCriteriaNum, $rubricQualityNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT RUBRIC_QUALITY_NUM 
FROM RUBRIC_CELL 
WHERE 
RUBRIC_CRITERIA_NUM = :criteria AND 
RUBRIC_QUALITY_NUM = :quality
SQL
	);
	$stmt->execute(array('criteria' => $rubricCriteriaNum, 'quality' => $rubricQualityNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetch();
	} else {
		return null;
	}
}

function sql_getRubricNumberFromRubricQuality($rubricQualityNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT RUBRIC_NUM 
FROM RUBRIC_QUALITY 
WHERE 
NUM = :cellparent
SQL
	);
	$stmt->execute(array('cellparent' => $rubricQualityNum));
	return $stmt->fetch();
}

function sql_setRubricCellContents($rubricCriteriaNum, $rubricQualityNum, $contents) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
UPDATE RUBRIC_CELL 
SET CONTENTS = :contents 
WHERE 
RUBRIC_CRITERIA_NUM = :criteria AND
RUBRIC_QUALITY_NUM = :quality
SQL
	);
	$stmt->execute(array(
	'contents' => $contents, 
	'criteria' => $rubricCriteriaNum, 
	'quality' => $rubricQualityNum));
}

function sql_getAllAssignments($teacherNum) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TITLE
FROM ASSIGNMENT
WHERE 
TEACHER_NUM = :teachernum
ORDER BY TITLE
SQL
	);
	$stmt->execute(array('teachernum' => $teacherNum));
	if($stmt->rowCount() > 0) {
		return $stmt->fetchAll();
	} else {
		return null;
	}
}

function sql_createAssignment($teacherNum, $title, $description) {
	global $conn;
	$stmt = $conn->prepare(
<<<SQL
INSERT INTO ASSIGNMENT 
(TEACHER_NUM, TITLE, DESCRIPTION) 
VALUES 
(:teacher, :title, :desc)
SQL
	);
	$stmt->execute(array(
	'teacher' => $teacherNum,
	'title' => $title,
	'desc' => $description));
}