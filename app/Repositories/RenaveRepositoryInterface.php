<?

namespace App\Repositories;

use App\DTO\{
    CreateRenaveDTO,
    UpdateRenaveDTO
};

use stdClass;

interface RenaveRepositoryInterface
{
    public function paginate(int $page = 1, int $totalPerPage = 15, ?string $filter): PaginationInterface;
    public function getAll(?string $filter): array;
    public function findOne(string $id): stdClass|null;
    public function delete(string $id): void;
    public function new(CreateRenaveDTO $dto): stdClass;
    public function update(UpdateRenaveDTO $dto): stdClass|null;

}
