<?php
/**
 * @copyright 2022 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\Actions\Add;

use Domain\Users\Entities\User;
use Domain\Users\DataStorage\UsersRepository;

class Command
{
    private $repo;

    public function __construct(UsersRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) { return new Response(null, $errors); }

        $user = new User((array)$req);

        try {
            $id  = $this->repo->save($user);
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
        if (!$req->username ) { $errors[] = 'missingUsername';  }
        if (!$req->role     ) { $errors[] = 'missingRole';      }
        if (!$req->authentication_method ) { $errors[] = 'missingAuthenticationMethod'; }
        return $errors;
    }
}
