<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TSModelServer
{
    private array $requestData;
    private string $model;
    private int $perPage = 10;
    private array $allowedWith = [];
    private array $with = [];

    public function allowWith(array $allowedWith): self
    {
        $this->allowedWith = $allowedWith;
        return $this;
    }

    /**
     * @return LengthAwarePaginator|ResourceCollection
     */
    public function respond(
        string $model,
        ?string $resource = null,
        ?callable $query = null
    )
    {
        $request = request();
        $this->model = $model;
        $this->requestData = $request->validate([
            'filters' => ['array'],
            'filters.*.field' => ['required', 'string'],
            'filters.*.operator' => ['string'],
            'filters.*.value' => ['required', 'string'],
            'perPage' => ['int'],
            'with' => ['array'],
            'with.*' => ['string'],
        ]);

        if (array_key_exists('perPage', $this->requestData)) {
            $this->perPage = $this->requestData['perPage'];
        }

        if (array_key_exists('with', $this->requestData)) {
            $this->with = $this->requestData['with'];
        }

        $result = $this->handleModel($query);
        return $resource !== null ? $resource::collection($result) : $result;
    }

    private function handleModel(?callable $subQuery) {
        $query = $this->model::query();
        if ($subQuery !== null) {
            $subQuery($query);
        }
        if ($this->allowedWith !== []) {
            $allowed = collect($this->allowedWith);
            $requested = collect($this->with);
            $possible = $allowed->intersect($requested)->toArray();
            $query->with($possible);
        }
        $this->handleFilters($query);
        return $query->paginate($this->perPage);
    }

    private function handleFilters(Builder $query): void
    {
        if (!array_key_exists('filters', $this->requestData)) {return;}
        foreach ($this->requestData['filters'] as $filter) {
            $field = $filter['field'];
            $operator = array_key_exists('operator', $filter) ? $filter['operator'] : '=';
            $value = $filter['value'];
            $query->where($field, $operator, $value);
        }
    }
}
