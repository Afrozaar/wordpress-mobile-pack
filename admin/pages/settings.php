<?php

    $themeManager = new PtPwaThemeManager(new PtPwaTheme());
    $theme = $themeManager->getTheme();
    $Pt_Pwa_Config = new Pt_Pwa_Config();

    $manifestManager = new PtPwaManifestManager(new PtPwaManifest());
    $manifest = $manifestManager->getManifest();

    if (isset($_POST["save"])) {

        // Manifest Details
        $manifest->setName($_POST['appName']);
        $manifest->setShortName($_POST['appName']);
        $manifest->setDescription($_POST['description']);

        // Theme Details
        $theme->setAppName($_POST['appName']);
        $theme->setShowClassicSwitch(isset($_POST['showClassicSwitch']));
        $theme->setMetaDescription($_POST['description']);
        $theme->setGTMID($_POST['GTMID']);
        $theme->setGATrackingCode($_POST['GATrackingCode']);
        $theme->setSocialShareKitButtons($_POST['socialMedia']);
        $theme->setAppEndpoint($_POST['appEndpoint']);

        if (isset($_POST["apiEndpoint"])) {
            $theme->setApiEndpoint($_POST['apiEndpoint']);
        }

        $theme->setTwitterSocialUrl($_POST['twitterSocialUrl']);
        $theme->setFacebookSocialUrl($_POST['facebookSocialUrl']);
        $theme->setInstagramSocialUrl($_POST['instagramSocialUrl']);
        $theme->setYoutubeSocialUrl($_POST['youtubeSocialUrl']);
        $theme->setDFTNetworkId($_POST['DFTNetworkId']);
        $theme->setIncludeTrailingSlashes(isset($_POST['includeTrailingSlashes']));

        if (empty($_POST['AdUnit'])) {
            $theme->setAdUnit("");
            $theme->setAdUnitSectionExtended(false);
        } else {
            $theme->setAdUnit($_POST['AdUnit']);
            $theme->setAdUnitSectionExtended(true);
        }

        $manifestManager->write();
        $themeManager->write();
        $Pt_Pwa_Config->enable_pwa(); // enable_pwa on save
    }

?>
<div id="wmpack-admin">
    <!-- set title -->
    <div class="spacer-20"></div>
    <h1>Publisher's Toolbox PWA</h1>
    <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/enable-pwa-btn.php'); ?>
    <div class="spacer-20"></div>
    <div class="settings">
        <div class="left-side">
            <!-- add nav menu -->
            <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>
            <!-- add content form -->
            <div class="details">
                <h2 class="title">App Settings</h2>
                <div class="spacer-20"></div>
                <div class="spacer-10"></div>
                <form id="core-settings" method="post" enctype="multipart/form-data">
                    <label>Application name</label>
                    <input type="text" name="appName" value="<?php echo $manifest->getName() ?>" />
                    <div class="spacer-20"></div>
                    <label>Application meta description</label>
                    <input type="text" name="description" value="<?php echo $manifest->getDescription() ?>" />
                    <div class="spacer-20"></div>
                    <label>Google Tag Manager ID</label>
                    <input type="text" name="GTMID" value="<?php echo $theme->getGTMID() ?>" />
                    <div class="spacer-20"></div>
                    <label>Google Analytics tracking code</label>
                    <input type="text" name="GATrackingCode" value="<?php echo $theme->getGATrackingCode() ?>" />
                    <div class="spacer-20"></div>
                    <label>Google Ad Manager network ID</label>
                    <input type="text" name="DFTNetworkId" value="<?php echo $theme->getDFTNetworkId() ?>" />
                    <div class="spacer-20"></div>
                    <label>Ad unit</label>
                    <input type="text" name="AdUnit" value="<?php echo $theme->getAdUnit() ?>" />
                    <div class="spacer-20"></div>
                    <label>API Endpoint</label>
                    <input type="text" name="apiEndpoint" value="<?php echo $theme->getApiEndpoint() ?>" disabled />
                    <div class="spacer-20"></div>
                    <label>Application Endpoint</label>
                    <input type="text" name="appEndpoint" value="<?php echo $theme->getAppEndpoint() ?>" />
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="showClassicSwitch" <?php echo $theme->getShowClassicSwitch() ? 'checked' : '' ?> /> Show classic site switch
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="includeTrailingSlashes" <?php echo $theme->getIncludeTrailingSlashes() ? 'checked' : '' ?> /> Include trailing slashes on routes
                    <div class="spacer-20"></div>
                    <div class="spacer-0"></div>
                    <h2 class="title">Social Media Sharing</h2>
                    <div class="spacer-20"></div>
                    <label>Twitter Social Link</label>
                    <input type="text" name="twitterSocialUrl" value="<?php echo $theme->getTwitterSocialUrl() ?>" />
                    <div class="spacer-20"></div>
                    <label>Instagram Social Link</label>
                    <input type="text" name="instagramSocialUrl" value="<?php echo $theme->getInstagramSocialUrl() ?>" />
                    <div class="spacer-20"></div>
                    <label>Facebook Social Link</label>
                    <input type="text" name="facebookSocialUrl" value="<?php echo $theme->getFacebookSocialUrl() ?>" />
                    <div class="spacer-20"></div>
                    <label>YouTube Social Link</label>
                    <input type="text" name="youtubeSocialUrl" value="<?php echo $theme->getYoutubeSocialUrl() ?>" />
                    <div class="spacer-20"></div>
                    <?php if (is_array($theme->getSocialShareKitButtons())) {
                        $socialShare = $theme->getSocialShareKitButtons();
                    } else {
                        $socialShare = [];
                    } ?>
                    <input type="checkbox" name="socialMedia[]" value="ssk-facebook" <?php echo in_array('ssk-facebook', $socialShare) ? 'checked' : '' ?> /> Enable Facebook sharing
                    <div class="spacer-10"></div>
                    <input type="checkbox" name="socialMedia[]" value="ssk-twitter" <?php echo in_array('ssk-twitter', $socialShare) ? 'checked' : '' ?> /> Enable Twitter sharing
                    <div class="spacer-10"></div>
                    <input type="checkbox" name="socialMedia[]" value="ssk-whatsapp" <?php echo in_array('ssk-whatsapp', $socialShare) ? 'checked' : '' ?> /> Enable WhatsApp sharing
                    <div class="spacer-10"></div>
                    <div class="submit">
                        <input type="submit" name="save" class="save" value="Save Settings" />
                    </div>
                </form>
            </div>
            <div class="right-side"></div>
        </div>
    </div>
