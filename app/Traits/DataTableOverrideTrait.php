<?php

namespace App\Traits;

use DateTime;
use Rappasoft\LaravelLivewireTables\Utilities\ColumnUtilities;
use Illuminate\Support\Facades\Schema;

trait DataTableOverrideTrait
{
    use \Rappasoft\LaravelLivewireTables\Traits\WithFilters;

    public function cleanFilters(): void
    {
        // Filter $filters values
        $this->filters = collect($this->filters)->filter(function ($filterValue, $filterName) {
            $filterDefinitions = $this->filters();

            // Ignore search
            if ($filterName === 'search') {
                return true;
            }

            // Filter out any keys that weren't defined as a filter
            if (!isset($filterDefinitions[$filterName])) {
                return false;
            }

            // Ignore null values
            if (is_null($filterValue)) {
                return true;
            }

            // Handle 'select' filters
            if ($filterDefinitions[$filterName]->isSelect()) {
                foreach ($this->getFilterOptions($filterName) as $optionValue) {
                    // If the option is an integer, typecast filter value
                    if (is_int($optionValue) && $optionValue === (int)$filterValue) {
                        return true;
                    }

                    // Strict check the value
                    if ($optionValue === $filterValue) {
                        return true;
                    }
                }
            }

            // Handle 'multiselect' filters
            if ($filterDefinitions[$filterName]->isMultiSelect() && is_array($filterValue)) {
                foreach ($filterValue as $selectedValue) {
                    if (!in_array($selectedValue, $this->getFilterOptions($filterName))) {
                        return false;
                    }
                }

                return true;
            }

            if ($filterDefinitions[$filterName]->isDate()) {
                // array_sum trick is a terse way of ensuring that PHP
                // did not do "month shifting"
                // (e.g. consider that January 32 is February 1)
                $dt = DateTime::createFromFormat("Y-m-d", $filterValue);
                $errors = $dt::getLastErrors();
                if (!is_array($errors)) {
                    $errors = [];
                }
                return $dt !== false && !array_sum($errors);
            }

            if ($filterDefinitions[$filterName]->isDatetime()) {
                // array_sum trick is a terse way of ensuring that PHP
                // did not do "month shifting"
                // (e.g. consider that January 32 is February 1)
                $dt = DateTime::createFromFormat("Y-m-d\TH:i", $filterValue);
                $errors = $dt::getLastErrors();
                if (!is_array($errors)) {
                    $errors = [];
                }
                return $dt !== false && !array_sum($errors);
                return $dt !== false && !array_sum($dt::getLastErrors());
            }

            return false;
        })->toArray();
    }

    public function applySearchFilter($query)
    {
        $searchableColumns = $this->getSearchableColumns();

        if ($this->hasFilter('search') && count($searchableColumns)) {
            $search = $this->getFilter('search');

            // Group search conditions together
            $query->where(function ($subQuery) use ($search, $query, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    // Does this column have an alias or relation?
                    $hasRelation = ColumnUtilities::hasRelation($column->column());

                    //COMMENTED OUT
                    // $selectedColumn = ColumnUtilities::mapToSelected($column->column(), $query);
                    $selectedColumn = null;

                    // If the column has a search callback, just use that
                    if ($column->hasSearchCallback()) {
                        // Call the callback
                        ($column->getSearchCallback())($subQuery, $search);
                    } elseif (!$hasRelation || $selectedColumn) { // If the column isn't a relation or if it was previously selected
                        $whereColumn = $selectedColumn ?? $column->column();

                        // TODO: Skip Aggregates
                        if (!$hasRelation) {
                            $whereColumn = Schema::hasColumn($query->getModel()->getTable(), $whereColumn) ? $query->getModel()->getTable() . '.' . $whereColumn : $whereColumn;
                        }

                        // We can use a simple where clause
                        $subQuery->orWhere($whereColumn, 'like', '%' . $search . '%');
                    } else {
                        // Parse the column
                        $relationName = ColumnUtilities::parseRelation($column->column());
                        $fieldName = ColumnUtilities::parseField($column->column());

                        // We use whereHas which can work with unselected relations
                        $subQuery->orWhereHas($relationName, function ($hasQuery) use ($fieldName, $search) {
                            $hasQuery->where($fieldName, 'like', '%' . $search . '%');
                        });
                    }
                }
            });
        }

        return $query;
    }
}
