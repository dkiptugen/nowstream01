<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

class CreateDataTable extends Command
    {
        protected $signature = 'make:datatable
                            {model : Model name}';

        protected $description = 'Generate intelligent DataTable class';

        public function handle(): void
            {
                $model = Str::studly($this->argument('model'));
                $modelClass = "App\\Models\\{$model}";
                $modelPath = app_path("Models/{$model}.php");

                if (!File::exists($modelPath)) {
                    $this->error("Model {$model} does not exist.");
                    return;
                }

                $instance = new $modelClass;
                $table = $instance->getTable();

                $columns = Schema::getColumnListing($table);

                $relationships = $this->detectRelationships($modelClass);

                $directory = app_path("Http/Datatables");
                File::ensureDirectoryExists($directory);

                $path = "{$directory}/{$model}Datatable.php";

                File::put($path, $this->buildStub($model, $columns, $relationships));

                $this->info("{$model}Datatable generated successfully.");
            }

        protected function detectRelationships(string $modelClass): array
            {
                $reflection = new ReflectionClass($modelClass);
                $methods = $reflection->getMethods();

                $relationships = [];

                foreach ($methods as $method) {
                    if ($method->class !== $modelClass) continue;
                    if ($method->getNumberOfParameters() > 0) continue;

                    $returnType = $method->getReturnType();
                    if ($returnType && str_contains($returnType, 'Illuminate\\Database\\Eloquent\\Relations')) {
                        $relationships[] = $method->getName();
                    }
                }

                return $relationships;
            }

        protected function buildStub(string $model, array $columns, array $relationships): string
            {
                $columnMap = collect($columns)
                    ->map(fn($col, $i) => "{$i} => '{$col}'")
                    ->implode(",\n        ");

                $searchColumns = collect($columns)
                    ->filter(fn($col) => !in_array($col, ['id', 'created_at', 'updated_at']))
                    ->map(fn($col) => "\$query->orWhere('{$col}', 'LIKE', \"%{\$search}%\");")
                    ->implode("\n                ");

                $withRelations = collect($relationships)
                    ->map(fn($rel) => "'{$rel}'")
                    ->implode(", ");

                return <<<PHP
<?php

namespace App\Http\Datatables;

use App\Models\\{$model};
use Illuminate\Support\Facades\Cache;

class {$model}Datatable
{
    protected array \$columns = [
        {$columnMap}
    ];

    public function data(\$request): array
    {
        if (!\$request->user()->can('view_{$model}')) {
            abort(403);
        }

        \$limit  = (int) \$request->input('length', 10);
        \$start  = (int) \$request->input('start', 0);
        \$draw   = (int) \$request->input('draw');

        \$orderIndex = (int) \$request->input('order.0.column', 0);
        \$dir        = \$request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        \$orderColumn = \$this->columns[\$orderIndex] ?? 'id';

        \$cacheKey = "datatable:{$model}:" . md5(json_encode(\$request->all()));

        return Cache::remember(\$cacheKey, 60, function () use (\$limit, \$start, \$draw, \$orderColumn, \$dir, \$request) {

            \$query = {$model}::query()
                ->with([{$withRelations}]);

            \$totalData = (clone \$query)->count();

            if (\$search = \$request->input('search.value')) {
                \$query->where(function (\$query) use (\$search) {
                    {$searchColumns}
                });
            }

            \$totalFiltered = (clone \$query)->count();

            \$rows = \$query
                ->orderBy(\$orderColumn, \$dir)
                ->offset(\$start)
                ->limit(\$limit)
                ->get();

            \$data = [];

            foreach (\$rows as \$row) {
                \$data[] = \$row->toArray();
            }

            return [
                'draw' => \$draw,
                'recordsTotal' => \$totalData,
                'recordsFiltered' => \$totalFiltered,
                'data' => \$data,
            ];
        });
    }
}
PHP;
            }
    }
