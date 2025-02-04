<?php


namespace App\Services;

use App\DTO\CreateFornecedorDTO;
use App\DTO\UpdateFornecedorDTO;
use App\Repositories\FornecedorRepositoryInterface;
use stdClass;

class FornecedorService
{

    // protected $repository;

    public function __construct(
        //principio de Inversão de Dependencia: trabalhar com uma Interface não importa qual classe esta implementada esta Interface  ex.:ORM eloquent laravel
        //no futuro for necessario trocar o ORM ex. para :Doctrine, não importa para qual ORM esta usando, quando invocar o metodo getAll(); atravez desta RepositoryInterface
        //retornara um array, porque que é oque esta definido na RepositoryInterface,(implementar esta RepositoryInterface para retornar/store/update/delete os dado do repositorio)
        //é obrigado seguir o padrão estabelecido na RepositoryInterface
        protected FornecedorRepositoryInterface $repository,
    ){}


    public function getAll(?string $filter): array
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
