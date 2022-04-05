/* Raid Selection */
function MovePlayer(player_element)
{
	var clicked_element = document.getElementById(player_element);
	var parent_element = clicked_element.parentNode;

	if( parent_element.id == "full_list" ) { 
		MoveToActiveRaid(player_element); 
	} else if( parent_element.id == "active_list") { 
		RemoveFromActiveRaid(player_element); 
	}

	SelectAllOptions();
}

function selectPlayerByDiv(divElement) {
	playerList = document.getElementById("player_list");

	playerDivs = playerList.childNodes;

	playerDivs.forEach( div => {
		grandChildren = div.childNodes;
		grandChildren.forEach( cdiv => {
			cdiv.style.display = "none";
		});
	});

	var charName = divElement.innerHTML.split(" - ")[1];

	document.getElementById("selected-player-name").innerHTML = charName;
	document.getElementById("selPlayer").style.display="block";

	var xmlhttp = new XMLHttpRequest();
	var jsonresults = "";
	xmlhttp.onreadystatechange = function() {
		if( this.readyState == 4 && this.status == 200 ) {
			jsonresults = JSON.parse(this.responseText);
			document.getElementById("selected-player-id").innerHTML=jsonresults['id'];
		}
	}
	xmlhttp.open("GET", "playerfunc.php?method=byname&name=" + charName, true);
	xmlhttp.send();
}

function SelectAllOptions() {
	var selectObj = document.getElementById("raid_list_select");

	for( i=0; i < selectObj.options.length; i++ ) {
		selectObj.options[i].selected = true;
	}
}

function MoveToActiveRaid(player_element) 
{
	var clicked_element = document.getElementById(player_element);
	var clickedElementNameAr = clicked_element.id.split('_');
	var playerId = clickedElementNameAr[1];
	var dest = document.getElementById("active_list");

	var lineText= clicked_element.innerHTML.trim().replace(/<\/?[^>]+(>|$)/g, "");
	var charName = lineText;

	dest.appendChild(clicked_element);

	var opt = document.createElement('option');
	opt.value = playerId;
	opt.innerHTML = charName;
	document.getElementById("raid_list_select").appendChild(opt);
}

function RemoveFromActiveRaid(player_element)
{
	var clicked_element = document.getElementById(player_element);
	var dest = document.getElementById("full_list");
	var raid_submit_list = document.getElementById("raid_list_select");
	var lineText= clicked_element.innerHTML.trim().replace(/<\/?[^>]+(>|$)/g, "");

	dest.appendChild(clicked_element);

	console.log("raid_submit_list name is " + raid_submit_list.name);

	for( var i=0; i < raid_submit_list.length; i++ ) {
		if( raid_submit_list.options[i].innerHTML == lineText ){
			raid_submit_list.remove(i);
		}
	}
}

function AwardLoot( method ){
	// Check to be sure both an item and a player are selected
	
	if( document.getElementById("selected-item-id").innerHTML == "" ){
		document.getElementById('lootErrorModalBody').innerHTML = "No item has been selected to award.";
		$('#lootErrorModal').modal()
		return;
	}

	if( document.getElementById("selected-player-id").innerHTML == "" ){
		document.getElementById('lootErrorModalBody').innerHTML = "No player has been selected to award loot to.";
		$('#lootErrorModal').modal()
		return;
	}
	var itemName = document.getElementById('selected-item-name').innerHTML;
	var playerName = document.getElementById('selected-player-name').innerHTML;
	document.getElementById("lootConfirmModalMethod").innerHTML = method;
	document.getElementById("lootConfirmModalBody").innerHTML = "Do you wish to award the following loot?<br>Item: <strong>" + itemName + "</strong><br>Player: <strong>" + playerName + "</strong><br>Method: <strong>" + method + "</strong>";
	$('#lootConfirmModal').modal();
}

/*
function refreshListOrder() {
	var raidId = document.getElementById("lootConfirmModalRaid").innerHTML;	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "https://www.mutiny-guild.com/index.php");
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	xhr.onreadystatechange = function() {
		if( xhr.readyState == 4 && xhr.status == 200 ) {
			jsonresults = JSON.parse(xhr.responseText);
			if( jsonresults['error'] == 'false' ) {
				console.log('New list order retVal = ' + jsonresults['list']);
				for( var i = 0; i < jsonresults['list'].length; i++ ) {
					console.log(jsonresults['list'][i]['charName'] + " is list order number " + jsonresults['list'][i]['position']);
					playerList = document.getElementById("player_list");

					playerDivs = playerList.childNodes;

					playerDivs.forEach( div => {
						grandChildren = div.childNodes;
						console.log("div index 0 id = " + grandChildren[0].id);
						grandChildren.forEach( cdiv => {
							cdiv.style.display = "block";
							console.log("this cdiv.id = " + cdiv.id);
							if( cdiv.id == "player_" + jsonresults['list'][i]['charName']) {
								cdiv.innerHTML = jsonresults['list'][i]['position'] + " - " + jsonresults['list'][i]['charName'];
							}
						});
					});
				}
			} else {
				console.log("Error refreshing list order.");
			}
		}
	};
	data = "do=refreshList&raidId=" + raidId;
	xhr.send(data);
}
*/

function refreshListOrder() {
	location.reload();
}

function confirmLootAward() {
	var itemName = document.getElementById('selected-item-name').innerHTML;
	var playerName = document.getElementById('selected-player-name').innerHTML;
	var itemId = document.getElementById('selected-item-id').innerHTML;
	var playerId = document.getElementById('selected-player-id').innerHTML;
	var awardMethod = document.getElementById("lootConfirmModalMethod").innerHTML;
	var listId = document.getElementById("lootConfirmModalList").innerHTML;
	var raidId = document.getElementById("lootConfirmModalRaid").innerHTML;	

	var xhr = new XMLHttpRequest();
    xhr.open("POST", "https://www.mutiny-guild.com/index.php");
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
		if(xhr.readyState == 4 && xhr.status == 200) {
			jsonresults = JSON.parse(xhr.responseText);
			if( jsonresults['error'] == 'false')
			{
				// Popup the loot success dialog
				document.getElementById("lootResultModalBody").innerHTML = "Loot assignment successful!";
				document.getElementById("btnLootResultDismiss").classList.remove('btn-danger');
				document.getElementById("btnLootResultDismiss").classList.add('btn-success');
				document.getElementById("btnLootResultDismiss").innerHTML = "Woot!";
				$('#lootResultModal').modal('show');
			} else {
				document.getElementById("lootResultModalBody").innerHTML = "Loot assignment failure!";
				document.getElementById("btnLootResultDismiss").classList.remove('btn-success');
				document.getElementById("btnLootResultDismiss").classList.add('btn-danger');
				document.getElementById("btnLootResultDismiss").innerHTML = "Shit!";
				$('#lootResultModal').modal('show');
			}
			console.log(jsonresults);
		}
    };
    
	data = 'do=award&itemId=' + itemId + '&playerId=' + playerId + '&listId=' + listId + '&awardMethod=' + awardMethod + "&raidId=" + raidId;
	console.log("outbound data");
	console.log(data);

    xhr.send(data);
}

function dismissEndRaidModal() {
	$('#endRaidModal').modal('hide');
}

function confirmEndRaid() {
	var includeLogLink = false;
	var logLink = document.getElementById("inpLogLink").value;

	if( logLink.length >= 58 ) {
		var includeLogLink = true;
		logLinkVal = logLink.split("//")[1].split("/")[2];
	}

	var raidId = document.getElementById("lootConfirmModalRaid").innerHTML;	
	var xhr = new XMLHttpRequest();
    xhr.open("POST", "https://www.mutiny-guild.com/index.php");
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
		if(xhr.readyState == 4 && xhr.status == 200) {
			jsonresults = JSON.parse(xhr.responseText);
			if( jsonresults['error'] == 'false')
			{
				// Popup the loot success dialog
				window.location.replace("https://www.mutiny-guild.com/index.php?section=ksk");
			} else {
				$('#endRaidFailModal').modal('show');
			}
			console.log(jsonresults);
		}
    };
    
	data = 'do=endraid&raidId=' + raidId;
	if( includeLogLink == true ) {
		data = data + '&logLink=' + logLinkVal;
	}

    xhr.send(data);
}

function dismissLootResultModal() {
	$('#lootResultModal').modal('hide');
	ClearPlayerSelection();
	ClearItemSelection();
}

function dismissLootConfirmModal() {
	$('#lootConfirmModal').modal('hide');
}

function ClearPlayerSelection()
{	
	document.getElementById("selected-player-name").innerHTML = "";
	document.getElementById("selPlayer").style.display="none";
	document.getElementById("selected-player-id").innerHTML = "";

	playerList = document.getElementById("player_list");

	playerDivs = playerList.childNodes;

	playerDivs.forEach( div => {
		grandChildren = div.childNodes;
		grandChildren.forEach( cdiv => {
			cdiv.style.display = "block";
		});
	});
}

function ClearItemSelection()
{
	var selectedIconDiv = document.getElementById("selected-item-icon");
	var selectedItemDiv = document.getElementById("selected-item-name");
	var clearX = document.getElementById('clear-selected-item');

	selectedItemDiv.innerHTML = "";
	selectedIconDiv.style.backgroundImage = "";
	clearX.style.display = "none";
}

function SelectLootItem(itemId)
{
	console.log("User has selected the item id " + itemId);

	// clear the search box
	var itemSearchBox = document.getElementById("txt-search");
	itemSearchBox.value = "";

	// clear the live-search results
	var liveSearchResults = document.getElementById("filter-records");
	liveSearchResults.innerHTML="";

	// set the item as selected
	var selectedIconDiv = document.getElementById("selected-item-icon");
	var selectedItemDiv = document.getElementById("selected-item-name");
	var selectedItemId = document.getElementById("selected-item-id");

	var xmlhttp = new XMLHttpRequest();
	var jsonresults = "";

	var clearX = document.getElementById('clear-selected-item');

	xmlhttp.onreadystatechange = function() {
		if( this.readyState == 4 && this.status == 200 ) {
			jsonresults = JSON.parse(this.responseText);
			console.log(jsonresults);
			imgUrl = 'https://wow.zamimg.com/images/wow/icons/large/' + jsonresults['data']['image_name'];
			selectedIconDiv.style.backgroundImage = "url('" + imgUrl + "')";
			selectedItemId.innerHTML=jsonresults['data']['id'];
			selectedItemDiv.innerHTML=jsonresults['data']['name'];
		}
	}
	xmlhttp.open("GET", "itemfunc.php?method=details&itemid=" + itemId, true);
	xmlhttp.send();

	clearX.style.display = "block";
}