<?

namespace App\Repositories;

use App\DTO\{
    CreateFornecedorDTO,
    UpdateFornecedorDTO
};

use stdClass;

interface FornecedorRepositoryInterface
{
    public function getAll(?string $filter): array;
    public function findOne(string $id): stdClass|null;
    public function delete(string $id): void;
    public function new(CreateFornecedorDTO $dto): stdClass;
    public function update(UpdateFornecedorDTO $dto): stdClass|null;

}
