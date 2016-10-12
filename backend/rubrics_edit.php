<?php
$needsAuthentication = true;
$needsAJAX = true;
$needsTeacher = true;
include "db.php";

$needsFunction = true;
include "functions.php";
$NUM = isset($_POST["NUM"]) ? $_POST["NUM"] : null;
$REQUEST = isset($_POST["REQUEST"]) ? $_POST["REQUEST"] : "";

#Makes sure the teacher owns the rubric we are editing.
$stmt = $conn->prepare(
<<<SQL
SELECT NUM, TEACHER_NUM, MAX_POINTS_PER_CRITERIA, SUBTITLE
FROM RUBRIC
WHERE
TEACHER_NUM = :teacherID AND
NUM = :num
SQL
);
$stmt->execute(array('teacherID' => $_SESSION["NUM"], 
					 'num' => $NUM));	
$countRubrics = $stmt->rowCount();

#Check count.
if($countRubrics == 1) {
	$row = $stmt->fetch();
	
	#Check how we are viewing the edit.
	switch ($REQUEST) {
		case "QUALITY":
			?>
			<div class="object subtext">
				<p>In a normal rubric, this section represents the cells colored in blue, as pictured below:</p>
			</div>
			<div class="object subtext">
				<table class="example">
					<tr>
						<td class="dark"></td>
						<td class="selectedexample">👎</td>
						<td class="selectedexample">👍</td>
						<td class="selectedexample">💯</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="object subtitle">
				<h2>Your Qualities</h2>
			</div>
			<?php
			$stmt = $conn->prepare(
<<<SQL
SELECT NUM, POINTS, QUALITY_TITLE
FROM RUBRIC_QUALITY
WHERE
RUBRIC_NUM = :rubric
SQL
			);
			$stmt->execute(array('rubric' => $row["NUM"]));	
			$countqualities = $stmt->rowCount();
			if($countqualities > 0) {
				$data = $stmt->fetchAll();
				listQuality("test", $data, $row["MAX_POINTS_PER_CRITERIA"]);
			} else {
				?>
				<div class="object subtext">
					<p>There's nothing here.</p>
				</div>
				<?php
			}
			break;
		
		
		
		
		
		
		
		case "CRITERIA":
			?>
			<div class="object subtext">
				<p>In a normal rubric, this section represents the cells colored in blue, as pictured below:</p>
			</div>
			<div class="object subtext">
				<table class="example">
					<tr>
						<td class="dark"></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="selectedexample"> 📗 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="selectedexample"> 📘 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="selectedexample"> 📙 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="object subtitle">
				<h2>Your Criteria</h2>
			</div>
			<?php
			$stmt = $conn->prepare(
<<<SQL
SELECT NUM, CRITERIA_TITLE
FROM RUBRIC_CRITERIA
WHERE
RUBRIC_NUM = :rubric
SQL
			);
			$stmt->execute(array('rubric' => $row["NUM"]));	
			$countcriteria = $stmt->rowCount();
			if($countcriteria > 0) {
				$data = $stmt->fetchAll();
				listCriterion("test", $data);
			} else {
				?>
				<div class="object subtext">
					<p>There's nothing here.</p>
				</div>
				<?php
			}
			break;
		
		
		
		
		
		
		#Default case, we want to see the edit options that we can do per rubric.
		case "VIEW":
		default:
?>
<div class="object subtitle">
	<h2><?php echo htmlentities($row["SUBTITLE"])?> </h2>
</div>
<a id="js_rubrics_edit_qualityview" class="object selectable white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>View qualities</h3></a>
<a id="js_rubrics_edit_criteriaview" class="object selectable white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>View criteria</h3></a>
<a id="js_rubrics_edit_mode" class="object selectable white" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Edit rubric cells</h3></a>
<a id="js_rubrics_edit_quality" class="object create" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Create new quality</h3></a>
<a id="js_rubrics_edit_criteria" class="object create" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Create new criteria</h3></a>
<a id="js_rubrics_edit_destroy" class="object destroy" href="#" data-num="<?php echo $row["NUM"] ?>"><div class="arrow"></div><h3>Destroy this rubric</h3></a>
<?php

			break;
	}
} else {
	showError("Whoops!", "That rubric number doesn't belong to you.", "Try selecting another rubric or refresh the page.", 400);
}
?>