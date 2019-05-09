<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Authentication;

use Domain\Auth\AuthInterface;
use Domain\Auth\Identity;
use Domain\Users\Entities\User;
use Domain\Users\DataStorage\UsersRepository;

class AuthenticationService implements AuthInterface
{
    private $repo;
    private $config;

    public function __construct(UsersRepository $repository, array $config)
    {
        $this->repo   = $repository;
        $this->config = $config;
    }

    public function identify(string $username): ?User
    {
        $user = $this->repo->loadByUsername($username);
        return $user ? $user : null;
    }

    public function externalIdentify(string $method, string $username): ?Identity
    {
        $o = $this->loadAuthenticationMethod($method);
        return $o->identify($username);
    }

    /**
     * Returns a User on success or null on failure
     *
     * @return User
     */
    public function authenticate(string $username, string $password): ?User
    {
        $user = $this->repo->loadByUsername($username);
        if ($user && $user->authentcationMethod) {
            switch ( $user->authentcationMethod) {
                case 'local':
                    return ($user->password == self::password_hash($password))
                        ? $user
                        : null;
                break;

                default:
                    $o = $this->loadAuthenticationMethod($user->authentcationMethod);
                    return $o->authenticate($username, $password);
            }
        }
    }

    public static function password_hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function getAuthenticationMethods(): array
    {
        return array_keys($this->config);
    }

    private function loadAuthenticationMethod(string $method): AuthenticationInterface
    {
        $class = $this->config[$method]['classname'];
        return new $class($this->config[$method]);
    }
}
