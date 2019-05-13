<?php
/**
 * @copyright 2016-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\Block;
use Web\Template;
use Web\Url;

use Domain\People\UseCases\Info\InfoResponse;
use Domain\People\Entities\Person;

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
        $links  = self::linksForPerson($person);

        $this->vars['title'] = parent::escape("{$person->firstname} {$person->lastname}");

        if ($this->outputFormat == 'html') {
            unset($links['self']);
            $this->blocks = [
                new Block('people/info.inc', [
                    'name'   => parent::escape($person),
                    'email'  => parent::escape($person->email),
                    'links'  => $links
                ])
            ];
        }
        else {
            $self  = Url::current_url(BASE_HOST);
            $model = (array)$person;
            $model['_links'   ] = $links;
            $model['_embedded'] = ['errors' => $response->errors];


            $this->blocks = [
                new Block('people/info.inc', ['response'=>$model])
            ];
        }
    }

    private static function linksForPerson(Person $person): array
    {
        $self = Url::current_url(BASE_HOST);
        return parent::isAllowed('people', 'update')
            ? ['self'=>$self, 'edit' => parent::generateUrl('people.update', ['id'=>$person->id])]
            : ['self'=>$self ];
    }
}
