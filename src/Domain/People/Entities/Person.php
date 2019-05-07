<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Entities;

class Person implements \JsonSerializable
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['id'       ])) { $this->id        = (int)$data['id'  ]; }
            if (!empty($data['firstname'])) { $this->firstname = $data['firstname']; }
            if (!empty($data['lastname' ])) { $this->lastname  = $data['lastname' ]; }
            if (!empty($data['email'    ])) { $this->email     = $data['email'    ]; }
        }
    }

    public function __toString()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function jsonSerialize()
    {
        return array_merge((array)$this, ['fullname'=>$this->__toString()]);
    }
}
