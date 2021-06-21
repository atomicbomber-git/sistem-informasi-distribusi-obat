<?php


namespace App\Casters;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AuditableCaster implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === "[]") {
            return null;
        }

        $auditableClassName = $model->auditable_type;
        $auditable = new $auditableClassName();

        return $auditable->forceFill(json_decode($value, true));
    }

    public function set($model, string $key, $value, array $attributes)
    {
//        ray()->model($model);
    }
}