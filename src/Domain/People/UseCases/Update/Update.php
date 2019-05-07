<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\UseCases\Update;

use Domain\People\Entities\Person;
use Domain\People\DataStorage\PeopleRepository;

class Update
{
    private $repo;

    public function __construct(PeopleRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(UpdateRequest $req): UpdateResponse
    {
        $errors = $this->validate($req);
        if ($errors) { return new UpdateResponse(null, $errors); }

        try {
            $id  = $this->repo->save(new Person((array)$req));
            $res = new UpdateResponse($id);
        }
        catch (\Exception $e) {
            $res = new UpdateResponse(null, [$e->getMessage()]);
        }
        return $res;
    }

    private function validate(UpdateRequest $req): array
    {
        $errors = [];
        if (!$req->firstname) { $errors[] = 'missingFirstname'; }
        if (!$req->lastname ) { $errors[] = 'missingLastname';  }
        return $errors;
    }
}
