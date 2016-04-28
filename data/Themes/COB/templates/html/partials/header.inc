<header class="siteHeader">
    <div class="siteHeader-container">
        <div class="site-title">
            <h1><a href="<?php echo BASE_URI; ?>"><?php echo self::escape(APPLICATION_NAME); ?></a></h1>
            <div class="site-location">City of Bloomington, Indiana &mdash; John Hamilton, Mayor</div>
        </div>
        <div class="site-utilityBar">
        <?php
            $this->addToAsset('scripts', 'https://bloomington.in.gov/static/fn1-releases/0.3-dev/js/dropdown.js');

            $this->_include('/partials/panel-widgets/User_menu.inc');
            $this->_include('/partials/panel-widgets/Administrator_menu.inc');
        ?>
        </div>
    </div>
    <?php $this->_include('partials/nav1.inc'); ?>
</header>
