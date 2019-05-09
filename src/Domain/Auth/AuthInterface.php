<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Auth;

use Domain\Users\Entities\User;

interface AuthInterface
{
    /**
     * See if the user is in the UsersRepository
     */
    public function identify(string $username): ?User;

    /**
     * See if the user is in an external identity system
     *
     * This is typically LDAP or ActiveDirectory, but could also
     * be a web service call.  There can be multiple identity systems
     * implemented in Site\Classes
     */
    public function externalIdentify(string $method, string $username): ?Identity;

    /**
     * See if a username and password match
     */
    public function authenticate(string $username, string $password): ?User;

    /**
     * Hash a password string to be stored in the UsersRepository
     */
    public static function password_hash(string $password): string;
}
