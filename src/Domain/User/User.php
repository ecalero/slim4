<?php
declare(strict_types=1);

namespace App\Domain\User;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Eloquent\Relations\HasOne;
//use League\OAuth2\Server\Entities\UserEntityInterface;
class User extends Model implements JsonSerializable 
{

	protected $table = 'USUARIO';
	public $timestamps = false;
    protected $primaryKey = 'IDUSUARIO';


    /**
     * @var int|null
     */
    private $IDUSUARIO;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->$IDUSUARIO;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'IDUSUARIO' => $this->IDUSUARIO,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
