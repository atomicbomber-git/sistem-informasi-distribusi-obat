<?php


namespace App\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/* @mixin Builder */
trait WithQueryBuilderHelpers
{
    public function selectQualify(array $selects)
    {
        return $this->select(array_map(fn($select) => $this->qualifyColumn($select), $selects));
    }

    public function filterBy(string $filterString, array $fields): self
    {
        return $this->when($filterString, function (Builder $builder) use ($filterString, $fields) {
            $filterStringParts = preg_split("~\"[^\"]*\"(*SKIP)(*F)|\s+~", trim($filterString));
            $filterStringParts = array_map(fn($part) => trim($part," \t\n\r\0\x0B\""), $filterStringParts);

            $this->where(function (Builder $builder) use ($fields, $filterStringParts) {
                foreach ($filterStringParts as $filterStringPart) {
                    foreach ($fields as $field) {

                        $fieldParts = explode('.', $field);

                        if (count($fieldParts) === 1) {
                            $builder->orWhere($fieldParts[0], "LIKE", "%{$filterStringPart}%");
                        } else {

                            $relationship = implode(
                                '.',
                                array_splice($fieldParts, 0, count($fieldParts) - 1),
                            );

                            $actualField = $fieldParts[array_key_last($fieldParts)];

                            $builder->orWhereHas($relationship, function (Builder $builder) use ($actualField, $filterStringPart) {
                                $builder->where($actualField, "LIKE", "%{$filterStringPart}%");
                            });
                        }
                    }
                }
            });
        });
    }

    /**
     * @param string|null $field
     * @param string $sortDirection
     * @param string|null $defaultField
     * @return WithQueryBuilderHelpers|Builder
     */
    public function sortBy(?string $field, string $sortDirection, string $defaultField = null): self
    {
        if ($field === null) {
            return $defaultField === null?
                $this :
                $this->orderBy($defaultField);
        }

        $mainQuery = $this->clone();

        $relationshipNames = explode(".", $field);
        $column = array_pop($relationshipNames);

        /** @var Model $currentRelatedModel */
        $currentRelatedModel = $mainQuery->getModel();

        foreach ($relationshipNames as $relationshipName) {
            /** @var BelongsTo|HasOne $relationshipObject */
            $relationshipObject = $currentRelatedModel->{$relationshipName}();
            $currentRelatedModel = $relationshipObject->getModel();

            $mainQuery = $mainQuery
                ->leftJoin(
                    $currentRelatedModel->getTable(),
                    $relationshipObject->getQualifiedOwnerKeyName(),
                    "=",
                    $relationshipObject->getQualifiedForeignKeyName(),
                );
        }

        return $mainQuery->orderBy(
            $currentRelatedModel->qualifyColumn($column),
            $sortDirection,
        );
    }
}