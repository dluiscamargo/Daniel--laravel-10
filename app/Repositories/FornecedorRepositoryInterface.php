<?

namespace App\Repositories;

use App\DTO\{
    CreateFornecedorDTO,
    UpdateFornecedorDTO
};

use stdClass;

interface FornecedorRepositoryInterface
{
    public function paginate(int $page = 1, int $totalPerPage = 15, ?string $filter): PaginationInterface;
    public function getAll(?string $filter): array;
    public function findOne(string $id): stdClass|null;
    public function delete(string $id): void;
    public function new(CreateFornecedorDTO $dto): stdClass;
    public function update(UpdateFornecedorDTO $dto): stdClass|null;

}
