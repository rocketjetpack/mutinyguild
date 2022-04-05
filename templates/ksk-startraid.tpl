<div style="padding-top: 60px;">
    <div class="row about-title" style="display: block;">
        <div class="col">
            <strong>Start Raid</strong> 
        </div>
    </div>
    <div class="row about-section opacity-90">
        <div class="col">
            Use this form to begin a raid.  All members on the KSK list are displayed on the left.  Click any members who should be considered present (in raid and reserve) to move them to the right.
			Enter a raid name, and hit Start Raid to proceed to the raid/loot management page.
        </div>
    </div>
	<div class="row about-section" style="padding-top: 15px;">
		<div class="col-md-5">
			<div class="row">
				<div class="col-md text-light bg-dark text-center">All KSK Members</div>
			</div>
			<div class="row overflow-auto" style="height: 450px;">
				<div class="col-md">
					<ul id="full_list">
					{foreach $pool as $player}
						<div id="player_{$player['uid']}" class="text-left" style="cursor: pointer;" {if $raid_in_progress == 0}onclick="MovePlayer('player_{$player['uid']}');"{/if}>
						<li id=player_{$player['uid']}_name>{$player['name']}</li>
						</div>
					{/foreach}		
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-5">
			<div class="row">
				<div class="col-md text-light bg-dark text-center">Raid/Reserve Members</div>
			</div>
			<div class="row overflow-auto" style="height: 450px;">
				<div class="col-md">
					<ul id="active_list">	
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="padding-top: 5px;">
		<div class="col-md-12 text-light bg-dark text-center">
			<form role="form" method="POST" action="index.php">
			<select style="display: none;" id="raid_list_select" name="raid[]" multiple >
			{if $raid_in_progress == 1}
				<input type="hidden" name="resume" value="true" />
				<input type="hidden" name="resume_raid_id" value="{$in_progress_raid_id}" />
			{/if}
			<input type="hidden" name="otp" value="{$oneTimeRaidId}" />
			<input type="hidden" name="do" value="handle_loot" />
			<input type="hidden" name="list" value="3" />
			<input id="inp_raid_name" {if $raid_in_progress}readonly="readonly"{/if} name="raid_name" placeholder="Enter a Raid Name" />&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-success my-2 my-md-0" type="submit">{if $raid_in_progress == 0}Start Raid{else}Resume Raid{/if}</button></form>
		</div>	
	</div>
</div>


<script src="https://ksk.mutiny-guild.com/scripts/raid.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function() {
	{foreach $in_progress_raid_members as $member}
	MovePlayer('player_{$member}')
	{/foreach}
	document.getElementById("inp_raid_name").value = "{$in_progress_raid_name}";
})
-->
</script>