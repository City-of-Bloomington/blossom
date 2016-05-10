<div class="nav1">
    <nav class="nav1-container">
    <?php
        /**
        * Navigation items are defined in /routes.inc
        *
        * We pulled them out so that theme designers could override this
        * file without worrying about changes to the navigation items
        * over time.
        */
        global $NAV1;

        foreach ($NAV1 as $item) {
            echo "<a href=\"$item[uri]\">{$this->_($item['msgid'])}</a>";
        }
    ?>
    </nav>
</div>
