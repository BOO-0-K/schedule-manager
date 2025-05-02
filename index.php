<?php 
include '_header.php'; 
$user = new User();
$users = $user->getUsersInfo();
?>
<div class="container">
    <div id='calendar'></div>
</div>
<div class="modal" id="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Update Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idx" value="">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title">
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" value="1" id="type1" checked>
                <label class="form-check-label" for="type1">
                    일반
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" value="2" id="type2">
                <label class="form-check-label" for="type2">
                    교육
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" value="3" id="type3">
                <label class="form-check-label" for="type3">
                    세미나
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" value="4" id="type4">
                <label class="form-check-label" for="type4">
                    회식
                </label>
            </div>
        </div>
        <div class="mb-3">
            <label for="startDate" class="form-label">Start Date</label>
            <input type="text" class="form-control" id="startDate">
        </div>
        <div class="mb-3">
            <label for="startTime" class="form-label">Start Time</label>
            <input type="text" class="form-control" id="startTime">
        </div>
        <div class="mb-3">
            <label for="endDate" class="form-label">End Date</label>
            <input type="text" class="form-control" id="endDate">
        </div>
        <div class="mb-3">
            <label for="endTime" class="form-label">End Time</label>
            <input type="text" class="form-control" id="endTime">
        </div>
        <div class="mb-3">
            <label for="participants" class="form-label">Participants</label>
            <select class="form-select" id="participants" name="participants" multiple="multiple"  style="width:100%">
                <?php
                    foreach ($users as $user):
                ?>
                <option value="<?=$user["idx"]?>"><?=$user["username"]?></option>
                <?php
                    endforeach;
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" value="<?=$myInfo["username"]?>" readonly>
            <input type="hidden" class="form-control" id="username" value="<?=$myInfo["username"]?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="delBtn" style="display:none">Delete</button>
        <button type="button" class="btn btn-primary" id="saveBtn" style="display:none">Save</button>
        <button type="button" class="btn btn-success" id="copyBtn" style="display:none">Copy</button>
      </div>
    </div>
  </div>
</div>
<?php include '_footer.php'; ?>