<div style="padding-top: 60px; padding-left: 5px; padding-right: 5px;">
    {if $isLootMaster}
    <div class="row border" style="width: 1200px; padding-bottom: 10px;">
        <div class="col-md-2 border">
            <div class="row" style="height: 43px;"><div class="col bg-light opacity-100 text-center" style="padding: 5px;"><strong>KSK Controls</strong></div></div>
        </div>
        <div class="col-md border">
            <div class="row" style="height: 43px;">
                <div class="col bg-light opacity-100 text-center" style="padding: 5px;"><a href='index.php?section=ksk&action=import' class="btn-sm btn-primary" type="button">Import List</a></div>
                <div class="col bg-light opacity-100 text-center" style="padding: 5px;"><a href='index.php?section=ksk&action=addmember' class="btn-sm btn-primary" type="button">Add Member</a></div>
                <div class="col bg-light opacity-100 text-center" style="padding: 5px;"><a href='index.php?section=ksk&action=startraid' class="btn-sm btn-primary" type="button">Start A Raid</a></div>
            </div>
        </div>
    </div>
    {/if}
    <div class="row" style="width: 1200px;">
        <div class="col-md-2 border">
            <div class="row"><div class="col bg-light opacity-100 text-center" style="padding: 5px;"><strong>List Order</strong></div></div>
        </div>
        <div class="col-md border">
            <div class="row"><div class="col bg-light opacity-100 text-center" style="padding: 5px;"><strong>Loot History</strong></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 bg-light opacity-90 overflow-auto" style="height: 600px;">
            <ul class="list-group">
            {foreach $pool as $player}
               <li class="list-group-item" style="padding: 5px; border: none;">{$player['pos']}. {$player['name']}</li>
            {/foreach}
            </ul>
        </div>
        <div class="col-md bg-light opacity-90 overflow-auto" style="height: 600px;">
            {if $lootlogentries == 0}
            <div class="row justify-content-center ">
                <div class="col-md">No Loot History</div>
            </div>
            {else}
            {foreach $items as $log_entry}
                <div class="row justify-content-center">
                    <div class="col-md-2 border rounded text-left">
                        {$log_entry['username']}
                    </div>
                    <div class="col-md-4 border rounded text-left">
                        <a href="https://tbc.wowhead.com/item={$log_entry['item']['data']['id']}" target="blank"">{$log_entry['item']['data']['name']}</a>
                        {if $log_entry['item']['data']['quality'] == "Epic"}<p style="display: inline; color: #9345ff !important">(Epic)</p>
                        {elseif $log_entry['item']['data']['quality'] == "Rare"}<p style="display: inline; color: #0070dd !important">(Rare)</p>
                        {elseif $log_entry['item']['data']['quality'] == "Uncommon"}<p style="display: inline; color: #0070dd !important">(Uncommon)</p>
                        {/if}
                    </div>
                    <div class="col-md-2 border rounded text-left">
                        {$log_entry['lootmode']}
                    </div>
                    <div class="col-md-2 border rounded text-left">
                        {$log_entry['raid_date']}
                    </div>
                </div>
                {/foreach}
            {/if}
        </div>
    </div>
</div>