<?php


namespace App\Services;

use App\DTO\CreateRenaveDTO;
use App\DTO\UpdateRenaveDTO;
use App\Repositories\RenaveRepositoryInterface;
use App\Repositories\PaginationInterface;
use stdClass;

class RenaveService
{

    // protected $repository;

    public function __construct(
        //principio de Inversão de Dependencia: trabalhar com uma Interface não importa qual classe esta implementada esta Interface  ex.:ORM eloquent laravel
        //no futuro for necessario trocar o ORM ex. para :Doctrine, não importa para qual ORM esta usando, quando invocar o metodo getAll(); atravez desta RepositoryInterface
        //retornara um array, porque que é oque esta definido na RepositoryInterface,(implementar esta RepositoryInterface para retornar/store/update/delete os dado do repositorio)
        //é obrigado seguir o padrão estabelecido na RepositoryInterface
        protected RenaveRepositoryInterface $repository,
    ){}


    public function paginate(
        int $page = 1,
        int $totalPerPage = 15,
        ?string $filter
    ): PaginationInterface{
        return $this->repository->paginate(
            page: $page,
            totalPerPage: $totalPerPage,
            filter: $filter,

        );
    }

    public function getAll(?string $filter): array
    {

        return $this->repository->getAll($filter);

    }

    public function findOne(string $id): stdClass|null
    {

        return $this->repository->findOne($id);

    }


    public function new(CreateRenaveDTO $dto): stdClass
    {

        return $this->repository->new($dto);

    }

    public function update(UpdateRenaveDTO $dto): stdClass|null
    {

        return $this->repository->update($dto);

    }

    public function delete(string $id): void
    {

        $this->repository->delete($id);

    }





}
