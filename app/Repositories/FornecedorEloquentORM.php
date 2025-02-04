<?

use App\DTO\CreateFornecedorDTO;
use App\DTO\UpdateFornecedorDTO;
use App\Models\Fornecedor;
use App\Repositories\FornecedorRepositoryInterface;

// use stdClass;

class FornecedorEloquentORM implements FornecedorRepositoryInterface
{

    public function __construct(
        protected Fornecedor $model

    ){}

    public function getAll(?string $filter): array
    {
        return $this->model
                    ->where(function ($query) use ($filter){

                        if($filter){
                           $query->where('name', $filter);
                           $query->orWhere('razao_social', 'like', "%{$filter}%");
                        }

                    })
                    ->all()
                    ->toArray();

    }

    public function findOne(string $id): stdClass|null
    {
        $fornecedor = $this->model->find($id);

        if (!$fornecedor) {
            return null;
        }

        //aqui Casting é a conversão de tipos de dados, como de um tipo primitivo para outro
        return (object) $fornecedor->toArray();

    }

    public function delete(string $id): void
    {
        $this->model->findOrFail($id)->delete();

    }

    public function new(CreateFornecedorDTO $dto): stdClass
    {

        $fornecedor = $this->model->create(
            (array) $dto
        );

        return (object) $fornecedor->toArray();

    }

    public function update(UpdateFornecedorDTO $dto): stdClass|null
    {
        if (!$fornecedor = $this->model->find($dto->id)) {
            return null;
        }

       $fornecedor->update(
            (array) $dto

       );

       return (object) $fornecedor->toArray();

    }
}
