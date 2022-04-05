<div style="padding-top: 60px;">
    <div class="row about-title">
		{if $resumedRaid}
        	<div class="col text-left">
            <strong>Resumed an active raid</strong> 
        </div>
		{/if}
		<div class="col" text-left">
			<form action="index.php" method="post">
				<input type="hidden" name="listid" value="{$listid}" />
				<input type="hidden" name="raidid" value="{$raidid}" />
				<input type="hidden" name="do" value="changeraidmembers" />
				<button class="btn btn-info my-2 my-md-0" type="submit">Change Raid Members</button>
			</form>
		</div>
		<div class="col text-right">
			<button class="btn btn-success my-2 my-md-0" type="button" onClick="$('#endRaidModal').modal('show');">End Raid</button>
		</div>
    </div>
	<div class="row about-section">
		<div class="col-md-12 text-center border">
			Enter an item name, then select a player to distribute the item to, and click the appropirate distrubtion method button.
		</div>
	</div>
	<div class="row about-section">
		<div class="col-md-4">
			<div class="row" style="padding-top: 25px;">
				<div class="col-md text-light bg-dark text-center">Item Search</div>
			</div>
			<div class="row" style="padding-top: 5px;">
				<div class="col-md">
					<form role="form">
						<div class="form-group">
							<input type="input" class="form-control input-lg" id="txt-search" onkeyup="ajaxSearch()" placeholder="Type item name">
						</div>
					</form>
					<div id="filter-records" class="overflow-auto" style="height: 250px;"></div>
					<div class="text-light bg-dark text-center">Selected Item</div>
					<div class="row" style="height: 50px;">
						<div id="selected-item-icon" class="col-md-3" style="width=50px; background-repeat: no-repeat; background-position: center;"></div>
						<div id="selected-item-name" class="col-md-7" font-weight-bold"></div>
						<div id="selected-item-id" style="display: none;"></div>
						<div id="clear-selected-item" class="col text-right font-weight-bold" style='cursor: pointer; display: none;' onClick="ClearItemSelection();">X</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-4">
			<div class="row" style="padding-top: 25px;">
				<div class="col-md text-light bg-dark text-center" onClick="refreshListOrder();">Player List</a></div>
			</div>
			<div class="overflow-auto" id="player_list" style="height: 285px;">
			{foreach $raid_list_details as $raid_member}
	<div class="row about-section" style="width: 90%;"><div id="player_{$raid_member['username']}" class="col text-center border" style="padding: 5px; margin-left: 25px; cursor: pointer;" onClick="selectPlayerByDiv(this);">{$raid_member['pos']} - {$raid_member['username']}</div></div>		{/foreach}
			</div>
			<div class="row" style="padding-top: 25px;">
				<div class="col-md text-light bg-dark text-center">Selected Player</div>
			</div>
			<div class="row" id="selPlayer" style="display: none;">
				<div id="selected-player-name" class="col-md-10 player-active text-center"></div>
				<div id="selected-player-id" style="display: none;"></div>
				<div id="clearselected-player" class="col-md text-right" onClick="ClearPlayerSelection();">X</div>
			</div>
		</div>
		<div class="col-md-1"></div>
		<div class="col">
			<div class="row" style="padding-top: 25px;">
				<div class="col-md text-light bg-dark text-center">Award Item</div>
			</div>
			<div class="row" style="padding-top: 50px;">
				<div class="col text-center">
				<button class="btn btn-success my-2 my-md-0" type="button" onClick="AwardLoot('bid');">Award via Bid</button>
				</div>
			</div>
			<div class="row" style="padding-top: 50px;">
				<div class="col text-center">
				<button class="btn btn-warning my-2 my-md-0" type="button" onClick="AwardLoot('roll');">Award via Roll</button>
				</div>
			</div>
			<div class="row" style="padding-top: 50px;">
				<div class="col text-center">
				<button class="btn btn-info my-2 my-md-0" type="button" onClick="AwardLoot('de');">Award via Disenchant</button>
				</div>
			</div>
			<div class="row" style="padding-top: 50px;">
				<div class="col text-center">
				<button class="btn btn-danger my-2 my-md-0" type="button" onClick="AwardLoot('other');">Award via Other</button>
				</div>
			</div>
		</div> 
	</div>
</div>

<script>
<!--
function ajaxSearch() {
	if( $('#txt-search').val().length < 3 ) {
		console.log("Too few characters specified in search box.");
		$('#filter-records').html("");
		return;
	}

	var xmlhttp = new XMLHttpRequest();
	var jsonresults = "";
	xmlhttp.onreadystatechange = function() {
		if( this.readyState == 4 && this.status == 200 ) {
			jsonresults = JSON.parse(this.responseText);
			var loopCount = 0;
			var output = '<div class="row" style="height: 50px;">';

			while( loopCount < jsonresults.data.num_items ) {
				itemId = jsonresults.data.items[loopCount].id;
				output += '<div class="col-md well well-sm">';
				output += '<div class="livesearch-result" onclick="SelectLootItem(\'' + itemId + '\' );">';
				output += jsonresults.data.items[loopCount].name;
				output += '</div>';
				output += '</div>';
				if( loopCount % 1 == 0 ) {
					output += '</div><div class="row" style="height: 50px;">';
				}
				loopCount++;
			}
			output += '</div>';
			$('#filter-records').html(output);
		}
	}
	xmlhttp.open("GET", "itemfunc.php?method=search&searchval=" + $('#txt-search').val(), true);
	xmlhttp.send();
}
-->
</script>

<div id="lootErrorModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Award Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="lootErrorModalBody" class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="lootConfirmModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Loot Award</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <div id="lootConfirmModalMethod" class="modal-body" style="display: none;"></div>
	  <div id="lootConfirmModalList" class="modal-body" style="display: none;">{$listid}</div>
	  <div id="lootConfirmModalRaid" class="modal-body" style="display: none;">{$raidid}</div>
      <div id="lootConfirmModalBody" class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" onClick="confirmLootAward();">Yes</button>
		<button type="button" class="btn secondary" onClick="dismissLootConfirmModal();">No</button>
      </div>
    </div>
  </div>
</div>

<div id="endRaidModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">End Raid</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="lootConfirmModalBody" class="modal-body">
        Are you sure you wish to end this raid now?
		<input class="form-control" name="logLink" id="inpLogLink" rows="3" placeholder="Enter log link (optional)"></input>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" onClick="confirmEndRaid();">Yes</button>
		<button type="button" class="btn secondary" onClick="dismissEndRaidModal();">No</button>
      </div>
    </div>
  </div>
</div>

<div id="endRaidFailModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">End Raid</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="endRaidFailModalBody" class="modal-body">
        There was an error ending this raid.  WTF could it be?
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger secondary" onClick="$('#endRaidFailModal').modal('hide');">Aww Shit</button>
      </div>
    </div>
  </div>
</div>

<div id="lootResultModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Loot Award</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="lootResultModalBody" class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button id="btnLootResultDismiss" type="button" class="btn btn-success" data-dismiss="modal" onClick="dismissLootResultModal();"">Woot</button>
      </div>
    </div>
  </div>
</div>

<script src="https://www.mutiny-guild.com/scripts/raid.js"></script>