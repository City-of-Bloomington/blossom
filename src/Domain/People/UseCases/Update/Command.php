<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\UseCases\Update;

use Domain\People\Entities\Person;
use Domain\People\DataStorage\PeopleRepository;

class Command
{
    private $repo;

    public function __construct(PeopleRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) { return new Response(null, $errors); }

        try {
            $id  = $this->repo->save(new Person((array)$req));
            $res = new Response($id);
        }
        catch (\Exception $e) {
            $res = new Response(null, [$e->getMessage()]);
        }
        return $res;
    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!$req->firstname) { $errors[] = 'missingFirstname'; }
        if (!$req->lastname ) { $errors[] = 'missingLastname';  }
        if (!$req->email    ) { $errors[] = 'missingEmail';     }
        return $errors;
    }
}
