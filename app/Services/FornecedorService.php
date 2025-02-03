<?php


namespace App\Services;

use stdClass;

class FornecedorService
{

    protected $repository;

    public function __construct()
    {

    }


    public function getAll(string $filter = ''): array
    {
        // dd($filter);
        return $this->repository->getAll($filter);

    }

    public function findOne(string $id): stdClass|null
    {

        return $this->repository->findOne($id);

    }


    public function new(
        string $name,
        string $razao_social,
        string $cnpj,
        string $email,
        string $telefone,
        string $endereco,
        string $numero,
        string $complemento,
        string $cidade,
        string $uf,
    ): stdClass
    {

        return $this->repository->new(
            $name,
            $razao_social,
            $cnpj,
            $email,
            $telefone,
            $endereco,
            $numero,
            $complemento,
            $cidade,
            $uf,
        );

    }

    public function update(
        string $id,
        string $name,
        string $razao_social,
        string $cnpj,
        string $email,
        string $telefone,
        string $endereco,
        string $numero,
        string $complemento,
        string $cidade,
        string $uf,
    ): stdClass|null
    {

        return $this->repository->update(
            $id,
            $name,
            $razao_social,
            $cnpj,
            $email,
            $telefone,
            $endereco,
            $numero,
            $complemento,
            $cidade,
            $uf,
        );

    }

    public function delete(string $id): void
    {

        $this->repository->delete($id);

    }





}
