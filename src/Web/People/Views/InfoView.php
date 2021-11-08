<?php
/**
 * @copyright 2016-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\View;

use Domain\People\Actions\Info\Response as InfoResponse;

class InfoView extends View
{
    public function __construct(InfoResponse $response)
    {
        parent::__construct();

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        $person = $response->person;

        $this->vars = [
            'title'  => "{$person->firstname} {$person->lastname}",
            'person' => $person
        ];
    }

    public function render(): string
    {
        return $this->twig->render("{$this->outputFormat}/people/info.twig", $this->vars);
    }
}
