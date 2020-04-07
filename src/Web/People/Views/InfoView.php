<?php
/**
 * @copyright 2016-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\Block;
use Web\Template;

use Domain\People\UseCases\Info\Response as InfoResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        $person = $response->person;

        $this->vars['title'] = parent::escape("{$person->firstname} {$person->lastname}");
		$this->blocks = [
            new Block('people/info.inc', ['person'=>$person])
        ];
    }
}
