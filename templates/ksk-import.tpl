<div style="padding-top: 60px;">
    <div class="row about-title" style="display: block;">
        <div class="col">
            <strong>Import KSK List</strong> 
        </div>
    </div>
    <div class="row about-section opacity-90">
        <div class="col">
            Use this form to import a new list. The list should be a comma-separated list of each player in the order they should be on the list. The primary use for this form should be to ensure the current list order matches
            the order from the KSK mod in-game.
        </div>
    </div>
    <div class="row about-section" style="padding: 10px;">
        <div class="col">
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="taNewList">New List</label>
                    <textarea class="form-control" name="listcontents" id="textareaNewList" rows="3" placeholder="Enter new list here"></textarea>
                </div>
                <div class="form-group">
                    <input type="checkbox" class="form-check-input" id="chkOverwriteList" onclick="toggleSubmit();">
                    <label class="form-check-label" for="chkOverwriteList">I know this will overwrite the current list and wish to proceed.</label>
                </div>
                <input type="hidden" name="do" value="overwritelist" />
                <button type="submit" id="btnSubmit" class="btn btn-primary" disabled>Submit</button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
function toggleSubmit() {
    var chkbox = document.getElementById('chkOverwriteList');

    console.log("Check state: " + chkbox.checked);

    if( chkbox.checked == true ) {
        $('#btnSubmit').prop('disabled', false);
    } else {
        $('#btnSubmit').prop('disabled', true);
    }
}
</script>