<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;

class SessionManager
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function set(string $name,  $value): self
    {

        if(is_null($this->get($name)))
            $this->session->set($name, $value);

        return $this;

    }

    public function has(string $name)
    {
        return $this->session->has($name);
    }

    public function get(string $name)
    {
        return $this->session->get($name);
    }

    public function getAll()
    {
        return $this->session->all();
    }

    public function remove(string $name): self
    {
        if(is_null($this->get($name)))
            throw new NoSuchIndexException(printf("Error ! Cause : session variable '%s' not found !", $name));

        $this->session->remove($name);

        return $this;

    }

    public function replace(string $name, array $values): self
    {

        if(is_null($this->get($name)))
            throw new NoSuchIndexException(printf("Error ! Cause : session variable '%s' not found !", $name));

        if(is_array($this->get($name)))
            $values = $this->addMissingKeysInArray($name, $values);

        $this->session->replace([
            $name => $values
        ]);

        //dd($this->get($name), $values);

        return $this;

    }

    public function removeAll(): self
    {
        $this->session->clear();

        return $this;
    }

    private function addMissingKeysInArray(string $name, array $keys)
    {

        $initialKeys = array_keys($this->get($name));

        foreach ($initialKeys as $index => $initialKey)
        {
            if(!array_key_exists($initialKey, $keys))
                $keys[$initialKey] = $this->get($name)[$initialKey];
        }
        //dd($keys, $initialKeys);
        return $keys;

    }

}