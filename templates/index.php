<?php
script('rocket_integration', 'chat');
style('rocket_integration', 'style');
?>

<?php if ($_['new'] === '1') { ?>
    <div class="messenger--add-members-info"> Add members to discussion by clicking the members button. </div>
<?php } ?>

<input type="hidden" name="rocketchat_token" value="<?php p($_['token']); ?>"/>

<iframe id="rocket-chat-iframe" src="<?php p($_['url']); ?>" allowfullscreen></iframe>
