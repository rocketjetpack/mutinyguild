<div style="padding-top: 60px;">
    <div class="row about-title" style="display: block;">
        <div class="col">
            <strong>Add KSK Member</strong> 
        </div>
    </div>
    <div class="row about-section opacity-90">
        <div class="col">
            Use this form to add a new member to the KSK list.  New members are added to the bottom of the list by default.
        </div>
    </div>
    <div class="row about-section" style="padding: 10px;">
        <div class="col">
            <form action="index.php" method="post" id="frmAddMember>
                <div class="form-group">
                    <label for="inpNewMember">New Member Name</label>
                    <input class="form-control" name="newmember" id="textareaNewList" rows="3" placeholder="Enter name here"></input>
                </div>
                <!--<div class="form-group">
                    <input type="checkbox" class="form-check-input" name="insertrandomly" id="chkOverwriteList" onclick="toggleSubmit();">
                    <label class="form-check-label" for="chkinsertrandom">Insert randomly</label>
                </div>-->
                <input type="hidden" name="do" value="addmember" />
                <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>