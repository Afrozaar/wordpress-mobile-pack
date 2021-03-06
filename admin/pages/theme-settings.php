<?php

    $themeManager = new PtPwaThemeManager(new PtPwaTheme());
    $theme = $themeManager->getTheme();

    $manifestManager = new PtPwaManifestManager(new PtPwaManifest());
    $manifest = $manifestManager->getManifest();

    $logoMsg = '';
    $hamburgerLogoMsg = '';
    $appIconMsg = '';

    if (!empty($_POST['save'])) {

        if (!empty($_FILES['logo']['name'])) {
            $logoUploaded = media_handle_upload('logo', 0);
            $logoUrl = wp_get_attachment_url($logoUploaded);

            if (is_wp_error($logoUploaded)) {
                $logoMsg = "There was a problem uploading the file. Please try again." . $logoUploaded->get_error_message();
            } else {
                $theme->setHeaderImage($logoUrl);
                $logoMsg = "The file has been uploaded successfully.";
            }
        }

        if (!empty($_FILES['hamburgerLogo']['name'])) {
            $hamburgerLogoUploaded = media_handle_upload('hamburgerLogo', 0);
            $hamburgerLogoUrl = wp_get_attachment_url($hamburgerLogoUploaded);

            if (is_wp_error($hamburgerLogoUploaded)) {
                $hamburgerLogoMsg = "There was a problem uploading the file. Please try again." . $logoUploaded->get_error_message();
            } else {
                $theme->setHamburgerImage($hamburgerLogoUrl);
                $hamburgerLogoMsg = "The file has been uploaded successfully.";
            }
        }

        if (!empty($_FILES['appIcon']['name'])) {
            $appIconUploaded = media_handle_upload('appIcon', 0);
            $mimeType = get_post_mime_type($appIconUploaded);
            $appIconArray = array(
                array(
                    "src"   => wp_get_attachment_image_src($appIconUploaded, 'pwa-x-small')[0],
                    "sizes" => "180x180",
                    "type"  => $mimeType
                ),
                array(
                    "src"   => wp_get_attachment_image_src($appIconUploaded, 'pwa-small')[0],
                    "sizes" => "192x192",
                    "type"  => $mimeType
                ),
                array(
                    "src"   => wp_get_attachment_image_src($appIconUploaded, 'pwa-medium')[0],
                    "sizes" => "384x384",
                    "type"  => $mimeType
                ),
                array(
                    "src"   => wp_get_attachment_image_src($appIconUploaded, 'pwa-large')[0],
                    "sizes" => "512x512",
                    "type"  => $mimeType
                ),
            );

            if (is_wp_error($appIconUploaded)) {
                $appIconMsg = "There was a problem uploading the file. Please try again. " . $appIconUploaded->get_error_message();
            } else {
                $theme->setAppIconUrl(wp_get_attachment_image_src($appIconUploaded, 'pwa-x-small')[0]);
                $manifest->setIcons($appIconArray);
                $appIconMsg = "The file has been uploaded successfully.";
            }
        }

        // Theme Colours
        $theme->setBmBurgerBarsBackground($_POST['bmBurgerBarsBackground']);
        $theme->setBmCrossBackground($_POST['bmCrossBackground']);
        $theme->setBmMenuBackground($_POST['bmMenuBackground']);
        $theme->setBmMenuBlockBackground($_POST['bmMenuBlockBackground']);
        $theme->setMenuTextColour($_POST['menuTextColour']);
        $theme->setSelectedText($_POST['selectedText']);
        $theme->setThemeColour($_POST['themeColour']);
        $theme->setBackgroundColour($_POST['backgroundColour']);
        $theme->setSelectedBackground($_POST['selectedBackground']);
        $theme->setTextColour($_POST['textColour']);
        $theme->setSectionSliderTextColor($_POST['sectionSliderTextColor']);
        $theme->setSectionSliderBackground($_POST['sectionSliderBackground']);
        $theme->setHighlightsColour($_POST['highlightsColour']);
        $theme->setBmMenuHeaderBackground($_POST['bmMenuHeaderBackground']);

        // Theme Details
        $theme->setSectionDownloadEnabled(isset($_POST['sectionDownloadEnabled']));
        $theme->setMultiSection(isset($_POST['multiSection']));
        $theme->setShowDateBlockOnFeedListItem(isset($_POST['showDateBlockOnFeedListItem']));
        $theme->setShowAllFeed(isset($_POST['showAllFeed']));
        $theme->setImageGalleryHeight($_POST['imageGalleryHeight']);
        $theme->setShowDatesOnList(isset($_POST['showDatesOnList']));
        $theme->setSearchLightTheme(isset($_POST['searchLightTheme']));
        $theme->setMenuLightIcons(isset($_POST['menuLightIcons']));
        $theme->setShowSearch(isset($_POST['showSearch']));
        $theme->setTopHeros((int)preg_replace('/[^0-9]/', '', $_POST['topHeros']));
        $theme->setSectionPrefix($_POST['sectionPrefix']);
        $theme->setShareTitlePrefix($_POST['shareTitlePrefix']);
        $theme->setInfiniteVerticalArticleScroll(isset($_POST['infiniteVerticalArticleScroll']));
        $theme->setInfiniteHorizontalArticleScroll(isset($_POST['infiniteHorizontalArticleScroll']));
        $theme->setNewsItemTimeFormat($_POST['newsItemTimeFormat']);
        $theme->setNewsItemDateFormat($_POST['newsItemDateFormat']);
        $theme->setDefaultFeedPageSize((int)preg_replace('/[^0-9]/', '', $_POST['defaultFeedPageSize']));
        $theme->setDnsPrefetch(explode(',', $_POST['dnsPrefetch']));

        // Manifest Colours
        $manifest->setThemeColor($_POST['themeColour']);
        $manifest->setBackgroundColor($_POST['backgroundColour']);

        $manifestManager->write();
        $themeManager->write();
        $Pt_Pwa_Config->enable_pwa(); // enable_pwa on save
    }

?>
<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null) {
        function changeColour(input) {
            input.nextElementSibling.style.background = input.value;
        }
    }
</script>
<div id="wmpack-admin">
    <div class="spacer-20"></div>
    <h1>Publisher's Toolbox PWA</h1>
    <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/enable-pwa-btn.php'); ?>
    <div class="spacer-20"></div>
    <div class="look-and-feel">
        <div class="left-side">
            <!-- add nav menu -->
            <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>
            <!-- add content form -->
            <div class="details">
                <h2 class="title">Setup Colour Scheme</h2>
                <div class="spacer-20"></div>
                <form method="post" id="color-settings" enctype="multipart/form-data">
                    <div class="holder">
                        <label>Menu icon (hamburger) colour</label>
                        <input value="<?php echo $theme->getBmBurgerBarsBackground() ?>" class="bmBurgerBarsBackground" type="text" name="bmBurgerBarsBackground" id="bmBurgerBarsBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement" style="background:<?php echo $theme->getBmBurgerBarsBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="bmCrossBackground">Close (x) button colour</label>
                        <input value="<?php echo $theme->getBmCrossBackground() ?>" type="text" class="bmCrossBackground" name="bmCrossBackground" id="bmCrossBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement2" style="background:<?php echo $theme->getBmCrossBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;"></div>
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="bmMenuBackground">Menu background colour</label>
                        <input value="<?php echo $theme->getBmMenuBackground() ?>" class="bmMenuBackground" type="text" name="bmMenuBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement3" style="background:<?php echo $theme->getBmMenuBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="bmMenuHeaderBackground">Menu header background colour</label>
                        <input value="<?php echo $theme->getBmMenuHeaderBackground() ?>" class="bmMenuHeaderBackground" type="text" name="bmMenuHeaderBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement3" style="background:<?php echo $theme->getBmMenuHeaderBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="bmMenuBlockBackground">Menu block containing menu items background colour</label>
                        <input value="<?php echo $theme->getBmMenuBlockBackground() ?>" class="bmMenuBlockBackground" type="text" name="bmMenuBlockBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement4" style="background:<?php echo $theme->getBmMenuBlockBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="selectedBackground">Selected background block of selected Item in menu</label>
                        <input value="<?php echo $theme->getSelectedBackground() ?>" class="selectedBackground" type="text" name="selectedBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement5" style="background:<?php echo $theme->getSelectedBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="menuTextColour">Colour of text in the menu</label>
                        <input value="<?php echo $theme->getMenuTextColour() ?>" class="menuTextColour" type="text" name="menuTextColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement5" style="background:<?php echo $theme->getMenuTextColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="selectedText">Colour of selected text in the menu</label>
                        <input value="<?php echo $theme->getSelectedText() ?>" class="selectedText" type="text" name="selectedText" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement6" style="background:<?php echo $theme->getSelectedText() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="themeColour">Default background colour and theme colour of app</label>
                        <input value="<?php echo $theme->getThemeColour() ?>" class="themeColour" type="text" name="themeColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement7" style="background:<?php echo $theme->getThemeColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="backgroundColour">Background colour of header and menu elements</label>
                        <input value="<?php echo $theme->getBackgroundColour() ?>" class="backgroundColour" type="text" name="backgroundColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div class="changedElement8" style="background:<?php echo $theme->getBackgroundColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="textColour">Default text content colour</label>
                        <input value="<?php echo $theme->getTextColour() ?>" class="textColour" type="text" name="textColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div style="background:<?php echo $theme->getTextColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="sectionSliderTextColor">Horizontal navigation slider menu text colour</label>
                        <input value="<?php echo $theme->getSectionSliderTextColor() ?>" class="sectionSliderTextColor" type="text" name="sectionSliderTextColor" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div style="background:<?php echo $theme->getSectionSliderTextColor() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="sectionSliderBackground">Horizontal navigation slider menu background colour</label>
                        <input value="<?php echo $theme->getSectionSliderBackground() ?>" class="sectionSliderBackground" type="text" name="sectionSliderBackground" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div style="background:<?php echo $theme->getSectionSliderBackground() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="highlightsColour">Highlights colour (Date blocks, underlines, links in content)</label>
                        <input value="<?php echo $theme->getHighlightsColour() ?>" class="highlightsColour" type="text" name="highlightsColour" placeholder="Enter hex value" onkeyup="changeColour(this);" />
                        <div style="background:<?php echo $theme->getHighlightsColour() ?>;height:20px; width: 40px; border:1px solid #E4E4E4; border-radius:2px;" />
                    </div>
                    <div class="spacer-15"></div>
                    <h2 class="title">Setup Post and Category options</h2>
                    <div class="spacer-15"></div>
                    <input type="checkbox" name="sectionDownloadEnabled" <?php echo $theme->getSectionDownloadEnabled() ? 'checked' : '' ?> /> Enable category download, this allows the latest content for the section to be available offline
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="multiSection" <?php echo $theme->getMultiSection() ? 'checked' : '' ?> /> Show child categories
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="showDateBlockOnFeedListItem" <?php echo $theme->getShowDateBlockOnFeedListItem() ? 'checked' : '' ?> /> Show date on feed items
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="showAllFeed" <?php echo $theme->getShowAllFeed() ? 'checked' : '' ?> /> Show home section, with all the latest posts
                    <div class="spacer-20"></div>
                    <div class="holder">
                        <label for="imageGalleryHeight">Image gallery height (as px or vh value)</label>
                        <input value="<?php echo $theme->getImageGalleryHeight() ?>" class="imageGalleryHeight" type="text" name="imageGalleryHeight" placeholder="Image gallery height" />
                    </div>
                    <div class="spacer-15"></div>
                    <input type="checkbox" name="showDatesOnList" <?php echo $theme->getShowDatesOnList() ? 'checked' : '' ?> /> Show dates on Image Thumbnail
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="showSearch" <?php echo $theme->getShowSearch() ? 'checked' : '' ?> /> Show menu search component
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="searchLightTheme" <?php echo $theme->getSearchLightTheme() ? 'checked' : '' ?> /> Do you want search bar foreground light theme?
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="menuLightIcons" <?php echo $theme->getMenuLightIcons() ? 'checked' : '' ?> /> Use light Icons?
                    <div class="spacer-20"></div>
                    <div class="holder">
                        <label for="topHeros">Number of top Featured posts</label>
                        <input value="<?php echo $theme->getTopHeros() ?>" class="topHeros" type="number" min="1" max="5" name="topHeros" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="sectionPrefix">Category prefix (keyword before category in url)</label>
                        <input value="<?php echo $theme->getSectionPrefix() ?>" class="sectionPrefix" type="text" name="sectionPrefix" placeholder="Category prefix" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="shareTitlePrefix">Share message suffix (this will be appended onto the share description - @somehandle)</label>
                        <input value="<?php echo $theme->getShareTitlePrefix() ?>" class="shareTitlePrefix" type="text" name="shareTitlePrefix" placeholder="Share Message Prefix" />
                    </div>
                    <div class="spacer-15"></div>
                    <input type="checkbox" name="infiniteVerticalArticleScroll" <?php echo $theme->getInfiniteVerticalArticleScroll() ? 'checked' : '' ?> /> Infinite vertical article scroll
                    <div class="spacer-20"></div>
                    <input type="checkbox" name="infiniteHorizontalArticleScroll" <?php echo $theme->getInfiniteHorizontalArticleScroll() ? 'checked' : '' ?> /> Infinite horizontal article scroll
                    <div class="spacer-20"></div>
                    <div class="holder">
                        <label for="newsItemDateFormat">News item date format</label>
                        <input value="<?php echo $theme->getNewsItemDateFormat() ?>" class="newsItemDateFormat" type="text" name="newsItemDateFormat" placeholder="eg Do MMM YYYY" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="newsItemTimeFormat">News item Time format</label>
                        <input value="<?php echo $theme->getNewsItemTimeFormat() ?>" class="newsItemTimeFormat" type="text" name="newsItemTimeFormat" placeholder="News item time format" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="defaultFeedPageSize">Feed page size</label>
                        <input value="10" class="defaultFeedPageSize" type="number" min="10" max="50" name="defaultFeedPageSize" />
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="dnsPrefetch">DNS Prefetch list (seperated by comma)
                            <em class="optional">*optional</em></label>
                        <textarea class="dnsPrefetch" type="textarea" name="dnsPrefetch" placeholder="DNS Prefetch List"><?php echo implode(",", $theme->getDnsPrefetch()) ?></textarea>
                    </div>
                    <div class="spacer-15"></div>
                    <h2 class="title">Setup Logo and Icons</h2>
                    <div class="spacer-15"></div>
                    <div class="holder" id="appIcon">
                        <label for="logo">App Logo <em class="required">* required</em></label>
                        <?php if ($theme->getHeaderImage()) { ?>
                            <img src="<?php echo $theme->getHeaderImage() ?>" style="max-height:80px" alt="header image" />
                            <br><?php } ?>
                        <input type="file" name="logo" style="padding: 7px;" <?php echo $theme->getHeaderImage() ? '' : 'required'; ?> />
                        <?php echo $logoMsg ? '<p class="description">' . $logoMsg . '</p>' : '' ?>
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="hamburgerLogo">Hamburger Logo <em class="required">* required</em></label>
                        <?php if ($theme->getHamburgerImage()) { ?>
                            <img src="<?php echo $theme->getHamburgerImage() ?>" style="max-height:80px" alt="hamburger image" />
                            <br><?php } ?>
                        <input type="file" name="hamburgerLogo" style="padding: 7px;" <?php echo $theme->getHamburgerImage() ? '' : 'required'; ?> />
                        <?php echo $hamburgerLogoMsg ? '<p class="description">' . $hamburgerLogoMsg . '</p>' : '' ?>
                    </div>
                    <div class="spacer-15"></div>
                    <div class="holder">
                        <label for="appIcon">App Icon <em class="required">* required</em></label>
                        <?php if ($manifest->getIcons()[0]['src']) { ?>
                            <img src="<?php echo $manifest->getIcons()[0]['src'] ?>" style="max-height:80px" alt="app icon" />
                            <br><?php } ?>
                        <input type="file" name="appIcon" style="padding: 7px;" <?php echo $manifest->getIcons()[0]['src'] ? '' : 'required'; ?> />
                        <?php echo $appIconMsg ? '<p class="description">' . $appIconMsg . '</p>' : '' ?>
                    </div>
                    <div class="spacer-15"></div>
                    <div class="submit">
                        <input type="submit" name="save" class="save" value="Save Look and Feel" />
                    </div>
                </form>
            </div>
            <div class="spacer-15"></div>
        </div>
    </div>
</div>
