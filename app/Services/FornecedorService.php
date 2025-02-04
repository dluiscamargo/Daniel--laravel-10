<?php


namespace App\Services;

use App\DTO\CreateFornecedorDTO;
use App\DTO\UpdateFornecedorDTO;
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


    public function new(CreateFornecedorDTO $dto): stdClass
    {

        return $this->repository->new($dto);

    }

    public function update(UpdateFornecedorDTO $dto): stdClass|null
    {

        return $this->repository->update($dto);

    }

    public function delete(string $id): void
    {

        $this->repository->delete($id);

    }





}
