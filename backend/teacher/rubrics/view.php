<?php
#Libraries.
include "../../restricted/headrubric.php";
?>
<div class="object subtitle">
	<h2><?php echo htmlentities($rubric["SUBTITLE"])?> </h2>
</div>
<a id="js_rubrics_edit_addquality" class="object create white" href="#" data-num="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Create or view qualities</h3></a>
<a id="js_rubrics_edit_addcriteria" class="object create white" href="#" data-num="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Create, edit, or view criteria</h3></a>
<a id="js_rubrics_edit_editrubric" class="object create white" href="#" data-num="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Edit this rubric</h3></a>
<a id="js_rubrics_edit_destroyquality" class="object warn white" href="#" data-num="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Choose and destroy qualities</h3></a>
<a id="js_rubrics_edit_destroycriteria" class="object warn create" href="#" data-num="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Choose and destroy criteria</h3></a>
<a id="js_rubrics_edit_destroyrubric" class="object destroy" href="#" data-num="<?php echo $rubric["NUM"] ?>"><div class="arrow"></div><h3>Destroy this rubric</h3></a><?php