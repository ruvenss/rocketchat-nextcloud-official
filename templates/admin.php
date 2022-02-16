<?php
script('rocket_integration', 'admin');
style('rocket_integration', 'style');

?>
<div class="rocket-info-wrapper">
    
        <div class="section">
            <h2><img src="/apps/rocket_integration/img/rocket-logo-black.png" width=15> Rocket Chat v.0.1.2 Alpha</h2>
      
            <div class="row">
                    <div class="col col-6">
                        <div id="rocketURL" class="infobox">
                            <p class="rocketp"> <img src="/apps/rocket_integration/img/admin-rocket.svg" class="infoicon"> Admin User ID<br>
                                <input type="text"
                                placeholder="Enter Rocket Chat Admin User ID"
                                class="input rocketinput"
                                required
                                name="userId"
                                value="<?= p($_['user_id']); ?>"
                                id="userId" spellcheck="false" readonly="readonly">
                                <a class="clipboardButton icon icon-clippy" data-clipboard-target="#userId"></a>
                            </p>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div id="rocketURL" class="infobox">
                            <p class="rocketp"> <img src="/apps/rocket_integration/img/key-rocket.svg" class="infoicon"> Admin Token<br>
                                <input type="text"
                                placeholder="Admin Token"
                                class="input rocketinput"
                                required
                                name="personalAccessToken"
                                value="<?= p($_['personal_access_token']); ?>"
                                id="personalAccessToken"  spellcheck="false" readonly="readonly"><a class="clipboardButton icon icon-clippy" data-clipboard-target="#personalAccessToken"></a>
                            </p>
                            
                        </div>
                    </div>
                    
                    <div class="col col-12">
                        <div id="rocketURL" class="infobox">
                            <p class="rocketp"> <img src="/apps/rocket_integration/img/login-svgrepo-com.svg" class="infoicon"> Auto generate Token and User ID <br><br>Your user name and password won't be saved. 
                                
                                    <input type="text" placeholder="rocket chat user" class="input rocketinput" name="rcuser" id="rcuser" require>
                                    <input type="password" placeholder="rocket chat password" class="input rocketinput" name="rcpassword" id="rcpassword" require>
                                    <input type="url" placeholder="https://your.rocket.chat.server.com" class="input rocketinput" name="url" id="rcurl" value="<?= p($_['rocketUrl']); ?>" required>
                                    <button class="button" id="rcconnect"> Connect and save</button>
                                   
                            </p>
                        </div>
                    </div>
            </div>
            
        </div>
        
    </form>
    
    <div class="section">
        <h2></h2>
        <p>
            <strong>Note !</strong> In order to invite members to a conversion about a file a Rocket Chat setting must
            be enabled.
        </p>
        <p>
            As a Rocket Chat admin go to "Administration -> Layout -> Interface" and check "Show top navbar in embedded layout".
        </p>
    </div>
</div>
