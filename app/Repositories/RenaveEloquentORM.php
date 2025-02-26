<?

namespace App\Repositories;

use App\DTO\CreateRenaveDTO;
use App\DTO\UpdateRenaveDTO;
use App\Models\Renave;
use App\Repositories\RenaveRepositoryInterface;
use App\Repositories\PaginationInterface;

 use stdClass;

class RenaveEloquentORM implements RenaveRepositoryInterface
{

    public function __construct(
        protected Renave $model

    ){}

    public function paginate(int $page = 1, int $totalPerPage = 15, ?string $filter): PaginationInterface
    {
        $result = $this->model
                    ->where(function ($query) use ($filter){

                        if($filter){
                           $query->where('name', $filter);
                           $query->orWhere('razao_social', 'like', "%{$filter}%");
                        }

                    })
                    ->paginate($totalPerPage, ['*'], 'page', $page);

        return new PaginationPresenter($result);
    }

    public function getAll(?string $filter): array
    {
        return $this->model
                    ->where(function ($query) use ($filter){
                        if($filter){
                           $query->where('name', $filter);
                           $query->orWhere('razao_social', 'like', "%{$filter}%");
                        }

                    })
                    ->get()
                    ->toArray();

    }

    public function findOne(string $id): stdClass|null
    {
        $renave = $this->model->find($id);

        if (!$renave) {
            return null;
        }

        //aqui Casting é a conversão de tipos de dados, como de um tipo primitivo para outro
        return (object) $renave->toArray();

    }

    public function delete(string $id): void
    {
        $this->model->findOrFail($id)->delete();

    }

    public function new(CreateRenaveDTO $dto): stdClass
    {

        $renave = $this->model->create(
            (array) $dto
        );

        return (object) $renave->toArray();

    }

    public function update(UpdateRenaveDTO $dto): stdClass|null
    {
        if (!$renave = $this->model->find($dto->id)) {
            return null;
        }

       $renave->update(
            (array) $dto

       );

       return (object) $renave->toArray();

    }
}
