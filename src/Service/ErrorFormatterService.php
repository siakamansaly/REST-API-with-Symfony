<?php

namespace App\Service;

class ErrorFormatterService
{
    /**
     * Add detail error
     * @return string
     */
    public function addDetailError(string $data=null, string $propertyPath, string $message): string
    {
        if ($data) {
            $data.= "\n";
        }
        $data.=  $propertyPath.': '.$message;
        return $data;
    }

    /**
     * Add violation error
     * @return array
     */
    public function addViolationError(string $propertyPath, string $message): array
    {
        $tab['code'] = uniqid();
        $tab['propertyPath'] = $propertyPath;
        $tab['message'] = $message;
        return $tab;
    }

    /**
     * Persit violation error
     * @return array
     */
    public function ErrorPersist(array $errors, string $details, string $title="An error occurred"): array
    {
        $data['title'] = $title;
        $data['detail'] = $details;
        $data['violations'] = $errors;
        return $data;
    }
}
