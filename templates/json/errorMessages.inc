<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

if (  isset($_SESSION['errorMessages'])) {
    print_r($_SESSION['errorMessages']);
      unset($_SESSION['errorMessages']);
}
